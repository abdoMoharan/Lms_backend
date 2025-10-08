<?php
namespace App\Repositories\Unit;


use Exception;
use App\Models\Unit;

use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Abstract\BaseRepository;
use App\Interfaces\Unit\UnitInterface;
use App\Http\Resources\Unit\UnitResource;

class UnitRepository extends BaseRepository
{
    public Unit $model;

    public function __construct(Unit $model)
    {
        $this->model = $model;
    }
    public function index($request)
    {
        try {
            $model = $this->model->query()->with(['createdBy', 'transLocale','course'])->filter($request->query())->get();
            if ($model->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No Unit found', []);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Unit retrieved successfully', UnitResource::collection($model)->response()->getData(true));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Unit found', $e->getMessage());
        }
    }
    public function store($request)
    {
        try {
            DB::beginTransaction();
            $data  = $request->getData();
            $model = $this->model->create($data);
            $model->load(['trans', 'createdBy','course']);
            DB::commit();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Unit created successfully', new UnitResource($model));
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Unit found', $e->getMessage());
        }
    }

    public function update($local, $request, $model)
    {

        try {
            $data = $request->getData();
            $model->update($data);
            $model->load(['trans', 'createdBy','course']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'eduction updated successfully', new UnitResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Unit found', []);
        }
    }
    public function delete($local, $model)
    {
        try {
            $model->delete();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Unit deleted successfully', []);
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No eduction found', []);
        }
    }

    public function show($local, $model)
    {
        try {
            $model->load(['trans', 'createdBy','course']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Unit retrieved successfully', new UnitResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Unit found', []);
        }
    }
    public function showDeleted()
    {
        $model = $this->model->getAllDeleted();
        if ($model->isEmpty()) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No deleted Unit found', []);
        }
        return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Deleted Unit retrieved successfully', UnitResource::collection($model));
    }
    public function restore($local, $id)
    {
        try {
            $this->model->restoreSoft($id);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Unit restored successfully');
        } catch (Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Unit found', []);
        }
    }

    public function forceDelete($local, $id)
    {
        try {
            $this->model->forceDeleteById($id);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Unit force deleted successfully');
        } catch (Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Unit found', []);
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
