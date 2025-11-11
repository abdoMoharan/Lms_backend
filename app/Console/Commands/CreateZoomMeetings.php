<?php
namespace App\Console\Commands;

use App\Models\GroupSession;
use App\Models\MeetingZoom;
use App\Services\ZoomService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateZoomMeetings extends Command
{
    protected $signature   = 'zoom:create-meetings';
    protected $description = 'Create Zoom meetings for upcoming sessions';

    protected $zoomService;

    public function __construct(ZoomService $zoomService)
    {
        parent::__construct();
        $this->zoomService = $zoomService;
    }

    public function handle()
    {
        $sessions = GroupSession::with('group')->whereDate('date', Carbon::now()->toDateString()) // الجلسات التي تاريخها اليوم
            ->where('is_meeting_created', false)                                                      // الجلسات التي لم يتم إنشاء رابط الاجتماع لها
            ->get();
        // إذا لم تكن هناك جلسات لتحديد الاجتماعات لها
        if ($sessions->isEmpty()) {
            $this->info('No upcoming sessions to create meetings for.');
            return; // إنهاء المهمة إذا لم توجد جلسات
        }
        foreach ($sessions as $session) {
            $meeting = $this->create_meeting($session); // تمرير الجلسة إلى دالة إنشاء الاجتماع
            // إذا تم إنشاء الاجتماع بنجاح
            if ($meeting && isset($meeting['join_url'])) {
                $meeting_zoom = MeetingZoom::create([
                    'group_session_id' => $session->id,
                    'zoom_id'          => $meeting['id'],
                    'host_id'          => $meeting['host_id'],
                    'host_email'       => $meeting['host_email'],
                    'topic'            => $meeting['topic'],
                    'start_time'       => $meeting['start_time'],
                    'duration'         => $meeting['duration'],
                    'timezone'         => $meeting['timezone'],
                    'start_url'        => $meeting['start_url'],
                    'join_url'         => $meeting['join_url'],
                    'password'         => $meeting['password'],
                    'is_meeting_created' => true,
                ]);
                // تحديث الجلسة برابط الاجتماع
                $session->update([
                    'is_meeting_created' => true, // تم إنشاء الاجتماع
                ]);

                $this->info("Created meeting for session on {$session->date}, link: {$meeting['join_url']}");
            } else {
                $this->error("Failed to create meeting for session on {$session->date}");
            }
        }
    }

    // دالة إنشاء الاجتماع
    public function create_meeting($session)
    {
                                                                                                     // إعدادات الاجتماع بناءً على بيانات الجلسة
        $start_time = Carbon::parse($session->date . ' ' . $session->start_time)->toIso8601String(); // تحديد وقت بدء الاجتماع استنادًا للجلسة

        // استدعاء خدمة Zoom لإنشاء الاجتماع
        $meeting = $this->zoomService->createMeeting('me', [
            'topic' => "Meeting for session {$session->id}",
            'type'       => 2,                         // مجدول
            'start_time' => $start_time,               // تحديد وقت البدء
            'duration'   => $session->group->duration, // تحديد مدة الاجتماع من الجلسة
            'timezone'   => 'Asia/Kolkata',            // المنطقة الزمنية
            'settings'   => [
                'host_video'                                => true,
                'participant_video'                         => false,
                'mute_upon_entry'                           => true,
                'request_permission_to_unmute_participants' => true,
                'audio'                                     => 'voip',
                'waiting_room'                              => true,
            ],
        ]);

        // إذا كانت الاستجابة ناجحة، أعد البيانات
        if (isset($meeting['join_url'])) {
            return $meeting; // إرجاع الرابط
        }

        return null; // في حال فشل الإنشاء، ارجع null
    }
}
