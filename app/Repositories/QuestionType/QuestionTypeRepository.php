<?php
namespace App\Repositories\QuestionType;

use Exception;
use App\Helpers\ApiResponse;
use App\Models\QuestionType;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Abstract\BaseRepository;
use App\Interfaces\QuestionType\QuestionTypeInterface;
use App\Http\Resources\QuestionType\QuestionTypeResource;

class QuestionTypeRepository extends BaseRepository
{
    public QuestionType $model;

    public function __construct(QuestionType $model)
    {
        $this->model = $model;
    }
    public function index($request)
    {
        try {
            $model = $this->model->query()->with(['createdBy', 'transLocale'])->filter($request->query())->get();
            if ($model->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No QuestionType found', []);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'QuestionType retrieved successfully', QuestionTypeResource::collection($model)->response()->getData(true));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No QuestionType found', $e->getMessage());
        }
    }
    public function store($request)
    {
        try {
            DB::beginTransaction();
            $data  = $request->getData();
            $model = $this->model->create($data);
            $model->load(['trans','createdBy']);
            DB::commit();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'QuestionType created successfully', new QuestionTypeResource($model));
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No QuestionType found', $e->getMessage());
        }
    }

    public function update($local, $request, $model)
    {

        try {
            $data = $request->getData();
            $model->update($data);
            $model->load(['trans','createdBy']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'eduction updated successfully', new QuestionTypeResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No QuestionType found', []);
        }
    }
    public function delete($local, $model)
    {
        try {
            $model->delete();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'QuestionType deleted successfully', []);
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No eduction found', []);
        }
    }

    public function show($local, $model)
    {
        try {
            $model->load(['trans', 'createdBy']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'QuestionType retrieved successfully', new QuestionTypeResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No QuestionType found', []);
        }
    }
    public function showDeleted()
    {
        $model = $this->model->getAllDeleted();
        if ($model->isEmpty()) {
            $model->load(['transLocale', 'createdBy']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No deleted QuestionType found', []);
        }
        return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Deleted QuestionType retrieved successfully', QuestionTypeResource::collection($model));
    }
    public function restore($local, $id)
    {
        try {
            $this->model->restoreSoft($id);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'QuestionType restored successfully');
        } catch (Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No QuestionType found', []);
        }
    }

    public function forceDelete($local, $id)
    {
        try {
            $this->model->forceDeleteById($id);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'QuestionType force deleted successfully');
        } catch (Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No QuestionType found', []);
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
