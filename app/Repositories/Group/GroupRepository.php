<?php
namespace App\Repositories\Group;

use App\Helpers\ApiResponse;
use App\Http\Abstract\BaseRepository;
use App\Http\Resources\Group\GroupResource;
use App\Models\Course;
use App\Models\Group;
use App\Models\GroupDay;
use App\Models\Week;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class GroupRepository extends BaseRepository
{
    public Group $model;
    public Course $course;

    public function __construct(Group $model, Course $course)
    {
        $this->model  = $model;
        $this->course = $course;

    }
    public function index($request)
    {
        try {
            $Groups = $this->model->query()->with(['teacher', 'course'])->get();
            if ($Groups->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No Groups found', []);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Groups retrieved successfully', GroupResource::collection($Groups)->response()->getData(true));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Groups found', $e->getMessage());
        }
    }



    public function store($request)
    {
        try {
            DB::beginTransaction();

            $data = $request->getData();

            // حفظ البيانات في جدول groups
            $model = $this->model->create($data);

            // جلب الكورس المرتبط بالمجموعة
            $course = $this->course->findOrFail($data['course_id']);

            // استرجاع عدد الأيام من الكورس
            $dayCount = $course->day_count;

            $weekIds = $data['week_ids']; // من الـ request

            // تحقق من أن عدد الأيام لا يتجاوز `day_count` في الكورس
            if (count($weekIds) > $dayCount) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'Number of days exceeds course day count', []);
            }

            // تحقق من وجود مجموعة في نفس الوقت للمعلم
            $group_day_exists = GroupDay::whereHas('group', function ($query) use ($data) {
                $query->where('teacher_id', $data['teacher_id']);
            })
                ->where('start_time', $data['start_time']) // تحقق من الوقت نفسه
                ->exists();

            if ($group_day_exists) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'This time is already reserved for this teacher.', []);
            }

            // تحقق من أن الفرق بين آخر مجموعة والمعلمة الجديدة هو ساعتين على الأقل
            $last_group = GroupDay::whereHas('group', function ($query) use ($data) {
                $query->where('teacher_id', $data['teacher_id']);
            })
                ->orderBy('start_time', 'desc')
                ->first();

            if ($last_group) {
                $last_start_time = \Carbon\Carbon::parse($last_group->start_time);
                $new_start_time  = \Carbon\Carbon::parse($data['start_time']);
                $difference      = $last_start_time->diffInHours($new_start_time);

                if ($difference < 2) {
                    return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'There must be at least a 2-hour difference between the last group and the new one.', []);
                }
            }

            // جلب الوحدات والدروس المرتبطة بالكورس
            $units   = $course->units;
            $lessons = [];
            foreach ($units as $unit) {
                $lessons = array_merge($lessons, $unit->lessons->toArray());
            }

            // توزيع الدروس على الأيام
            $lessonCount   = count($lessons);
            $dayCount      = count($weekIds);                // عدد الأيام التي اختارها المعلم
            $lessonsPerDay = ceil($lessonCount / $dayCount); // توزيع الدروس بالتساوي

            $lessonIndex = 0;

                                                                                                      // تحديد التواريخ للأيام المختارة (السبت والأربعاء)
            $daysOfWeek = DB::table('weeks')->whereIn('id', $weekIds)->pluck('day', 'id')->toArray(); // الحصول على الأيام المختارة من جدول weeks
            $dateOffset = 0;

            // تاريخ البداية الذي يدخله المعلم
            $startDate = \Carbon\Carbon::parse($data['start_date']);

            // إنشاء سجلات جديدة في جدول group_days بناءً على week_ids
            foreach ($weekIds as $weekId) {
                // إضافة البيانات إلى جدول group_days
                $groupDay = $model->groupDays()->create([
                    'start_time'   => $data['start_time'],
                    'session_time' => $data['session_time'],
                    'week_id'      => $weekId,
                ]);

                // تحديد اليوم المناسب من الأيام المختارة (السبت أو الأربعاء)
                $week    = Week::find($weekId);
                $dayName = $daysOfWeek[$weekId]; // الحصول على اسم اليوم من الأيام المختارة

                // البحث عن أقرب تاريخ لهذا اليوم من التاريخ المدخل
                $date = $this->getNextAvailableDate($startDate, $dayName);

                // توزيع الدروس على الأيام المختارة
                $lessonsForDay = array_slice($lessons, $lessonIndex, $lessonsPerDay);

                foreach ($lessonsForDay as $lesson) {
                    // إنشاء جلسة للمجموعة على هذا اليوم
                    $groupSession = \App\Models\GroupSession::create([
                        'date'      => $date->toDateString(),
                        'group_id'  => $model->id,    // المعرف الخاص بالمجموعة
                        'day_id'    => $groupDay->id, // ربط session بـ groupDay
                        'lesson_id' => $lesson['id'], // استخدام ID الدرس هنا
                    ]);
                    $lessonIndex++;

                    // إضافة أسبوع لكل يوم لتخصيص الدرس التالي
                    $date = $date->addWeek();
                }

                // تحديث التحديد للانتقال بين الأيام المختارة (مثلاً السبت والأربعاء)
                $dateOffset = ($dateOffset + 1) % 2;
            }

            // **حساب عدد الجلسات** (sessions) في جدول GroupSession بناءً على group_id:
            $sessionsCount = \App\Models\GroupSession::where('group_id', $model->id)->count();

                                                                   // **حساب hours_count** بناءً على عدد الجلسات وعدد الساعات لكل جلسة (duration):
            $hoursCount = $sessionsCount * (int) $model->duration; // ضرب عدد الجلسات في مدة كل جلسة

            // تحديث hours_count في المجموعة
            $model->update([
                'hours_count' => $hoursCount,
            ]);

            // إعادة تحميل العلاقة بعد إضافة البيانات
            $model->load(['course', 'teacher', 'groupDays', 'groupSession']);

            DB::commit();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Groups created successfully', new GroupResource($model));
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Groups found', $e->getMessage());
        }
    }

// دالة لتحديد أقرب يوم من الأيام المختارة بناءً على التاريخ المدخل
    private function getNextAvailableDate($startDate, $dayName)
    {
        // نستخدم Carbon لتحديد أقرب يوم بناءً على اليوم المدخل
        $carbonDate = \Carbon\Carbon::parse($startDate)->next($dayName);

        // إذا كان التاريخ المدخل هو نفس اليوم المختار أو بعده، نستخدمه
        if ($startDate->isSameDay($carbonDate) || $startDate->gt($carbonDate)) {
            return $startDate; // نستخدم التاريخ المدخل إذا كان في نفس اليوم أو بعده
        }

        return $carbonDate;
    }

    public function update($local, $request, $model)
    {
        try {
            $data = $request->getData();
            $model->update($data);
            $model->load(['teacher', 'course']);

            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Groups updated successfully', new GroupResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Groups found', []);
        }
    }
    public function delete($local, $model)
    {
        try {
            $model->delete();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Groups deleted successfully', []);
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Groups found', []);
        }
    }
    public function show($local, $model)
    {
        try {
            $model->load(['teacher', 'course','groupDays','groupSession']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Groups retrieved successfully', new GroupResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Groups found', []);
        }
    }

}
