<?php
namespace App\Repositories\Subject;

use App\Helpers\ApiResponse;
use App\Http\Resources\Subject\SubjectResource;
use App\Interfaces\Subject\SubjectInterface;
use App\Models\Subject;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SubjectRepository implements SubjectInterface
{
    public Subject $model;

    public function __construct(Subject $model)
    {
        $this->model = $model;
    }
    public function index($request)
    {
        try {
            $subject = $this->model->query()->with(['createdBy', 'transLocale', 'educationalStage','semester','grade'])->filter($request->query())->get();
            if ($subject->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No Subjects found', []);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Subjects retrieved successfully', SubjectResource::collection($subject)->response()->getData(true));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Subjects found', $e->getMessage());
        }
    }
    public function store($request)
    {
        try {
            DB::beginTransaction();
            $data   = $request->getData();
            $subject = $this->model->create($data);
            $subject->load(['transLocale', 'educationalStage','semester','grade']);
            DB::commit();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Subjects created successfully', new SubjectResource($subject));
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Subjects found', $e->getMessage());
        }
    }

    public function update($local, $request, $model)
    {

        try {
            $data = $request->getData();
            $model->update($data);
            $model->load(['transLocale', 'educationalStage','semester','grade']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'eduction updated successfully', new SubjectResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Subjects found', []);
        }
    }
    public function delete($local, $model)
    {
        try {
            $model->delete();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Subjects deleted successfully', []);
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No eduction found', []);
        }
    }

    public function show($local, $model)
    {
        try {
            $model->load(['createdBy', 'transLocale', 'educationalStage','semester','grade']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Subjects retrieved successfully', new SubjectResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Subjects found', []);
        }
    }
    public function showDeleted()
    {
        $subject = $this->model->getAllDeleted();
        if ($subject->isEmpty()) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No deleted Subjects found', []);
        }
        return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Deleted Subjects retrieved successfully', SubjectResource::collection($subject));
    }
    public function restore($local, $id)
    {
        try {
            $this->model->restoreSoft($id);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Subjects restored successfully');
        } catch (Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Subjects found', []);
        }
    }

    public function forceDelete($local, $id)
    {
        try {
            $this->model->forceDeleteById($id);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Subjects force deleted successfully');
        } catch (Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Subjects found', []);
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
