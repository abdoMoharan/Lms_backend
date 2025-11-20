<?php
namespace App\Repositories\Exam;

use App\Helpers\ApiResponse;
use App\Http\Abstract\BaseRepository;
use App\Http\Resources\Exam\ExamResource;
use App\Models\Exam;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamRepository extends BaseRepository
{
    public Exam $model;

    public function __construct(Exam $model)
    {
        $this->model = $model;
    }
    public function index($request)
    {
        try {
            $Exams = $this->model->query()->with(['teacher', 'groupSession', 'questions'])->where('teacher_id', Auth::user()->id)->filter($request->query())->get();
            if ($Exams->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No Exams found', []);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Exams retrieved successfully', ExamResource::collection($Exams)->response()->getData(true));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Exams found', $e->getMessage());
        }
    }

    public function store($request)
    {
        $data = $request->getData();
        try {
            DB::beginTransaction();

            $exam = $this->model->create([
                'name'             => $data['name'],
                'group_session_id' => $data['group_session_id'],
                'teacher_id'       => Auth::user()->id,
                'description'      => $data['description'] ?? null,
                'duration'         => $data['duration'] ?? null,
                'start_date'       => $data['start_date'] ?? null,
                'end_date'         => $data['end_date'] ?? null,
                'total'            => $data['total'] ?? 0,
            ]);

            $totalMarks = 0;

            foreach ($data['questions'] as $qData) {
                $question = $exam->questions()->create([
                    'question_text' => $qData['question_text'],
                    'mark'          => $qData['mark'],
                ]);

                $totalMarks += $qData['mark'];

                foreach ($qData['options'] as $optData) {
                    $question->options()->create($optData);
                }
            }
            $exam->update(['total' => $totalMarks]);
            $exam->load(['teacher', 'groupSession', 'questions']);
            DB::commit();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Exam created successfully', new ExamResource($exam));
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::apiResponse(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, 'Error creating exam', $e->getMessage());
        }
    }

    public function update($local, $request, $model)
    {

        try {
            $data = $request->getData();
            $model->update($data);
            $model->load(['trans', 'teacher', 'course']);

            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'eduction updated successfully', new ExamResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Exams found', []);
        }
    }
    public function delete($local, $model)
    {
        try {
            $model->delete();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Exams deleted successfully', []);
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No eduction found', []);
        }
    }

    public function show($model)
    {
        try {
            $model->load(['trans', 'teacher', 'course']);

            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Exams retrieved successfully', new ExamResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Exams found', []);
        }
    }
    public function showDeleted()
    {
        $model = $this->model->getAllDeleted();
        $model->load(['transLocale', 'teacher', 'course']);

        if ($model->isEmpty()) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No deleted Exams found', []);
        }
        return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Deleted Exams retrieved successfully', ExamResource::collection($model));
    }
    public function restore($local, $id)
    {
        try {
            $this->model->restoreSoft($id);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Exams restored successfully');
        } catch (Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Exams found', []);
        }
    }

    public function forceDelete($local, $id)
    {
        try {
            $this->model->forceDeleteById($id);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Exams force deleted successfully');
        } catch (Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Exams found', []);
        }
    }
    public function multi_actions($local, $request)
    {
        $data = $request->validate([
            'type'    => 'required',
            'records' => 'required|array',
        ]);

        switch ($data['type']) {
            case 'delete':
                $models = $this->model->findMany($request['records']);
                foreach ($models as $item) {
                    $item->delete();
                }
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'deleted successfully');
                break;
            case 'status_active':
                $models = $this->model->findMany($request['records']);
                foreach ($models as $item) {
                    $item->update(['status' => 1]);
                }
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'update status  successfully');
                break;
            case 'status_inactive':
                $models = $this->model->findMany($request['records']);
                foreach ($models as $item) {
                    $item->update(['status' => 0]);
                }
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'update status  successfully');
                break;
            case 'restore':
                $models = $this->model->onlyTrashed()->findMany($request['records']);
                foreach ($models as $item) {
                    $item->restore();
                }
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Records restored successfully');
                break;
            case 'force-delete':
                $models = $this->model->onlyTrashed()->findMany($request['records']);
                foreach ($models as $item) {
                    $item->forceDelete();
                }
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Records permanently deleted');
                break;
            default:
                return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No  found', []);
                break;
        }
    }
}
