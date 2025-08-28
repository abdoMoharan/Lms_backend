<?php
namespace App\Repositories\Chapter;

use App\Helpers\ApiResponse;
use App\Http\Resources\Chapter\ChapterResource;
use App\Interfaces\Chapter\ChapterInterface;
use App\Models\ClassRoom;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ChapterRepository implements ChapterInterface
{
    public ClassRoom $model;

    public function __construct(ClassRoom $model)
    {
        $this->model = $model;
    }
    public function index($request)
    {
        try {
            $chapters = $this->model->query()->with(['createdBy', 'transLocale', 'educationalStage'])->filter($request->query())->get();
            if ($chapters->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No chapters found', []);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'chapters retrieved successfully', ChapterResource::collection($chapters)->response()->getData(true));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No chapters found', $e->getMessage());
        }
    }
    public function store($request)
    {
        try {
            DB::beginTransaction();
            $data     = $request->getData();
            $chapters = $this->model->create($data);
            DB::commit();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'chapters created successfully', new ChapterResource($chapters));
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No chapters found', $e->getMessage());
        }
    }

    public function update($local, $request, $model)
    {

        try {
            $data = $request->getData();
            $model->update($data);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'eduction updated successfully', new ChapterResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No chapters found', []);
        }
    }
    public function delete($local, $model)
    {
        try {
            $model->delete();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'chapters deleted successfully', []);
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No eduction found', []);
        }
    }

    public function show($local, $model)
    {
        try {
            $model->load(['createdBy', 'transLocale', 'educationalStage']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'chapters retrieved successfully', new ChapterResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No chapters found', []);
        }
    }
    public function showDeleted()
    {
        $chapters = $this->model->getAllDeleted();
        if ($chapters->isEmpty()) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No deleted chapters found', []);
        }
        return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Deleted chapters retrieved successfully', ChapterResource::collection($chapters));
    }
    public function restore($local, $id)
    {
        try {
            $this->model->restoreSoft($id);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'chapters restored successfully');
        } catch (Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No chapters found', []);
        }
    }

    public function forceDelete($local, $id)
    {
        try {
            $this->model->forceDeleteById($id);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'chapters force deleted successfully');
        } catch (Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No chapters found', []);
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
