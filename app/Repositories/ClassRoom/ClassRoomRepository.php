<?php
namespace App\Repositories\ClassRoom;


use Exception;
use App\Helpers\ApiResponse;
use App\Models\class_room;
use App\Models\ClassRoom;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Interfaces\ClassRoom\ClassRoomInterface;
use App\Http\Resources\ClassRoom\ClassRoomResource;

class ClassRoomRepository implements ClassRoomInterface
{
    public ClassRoom $model;

    public function __construct(ClassRoom $model)
    {
        $this->model = $model;
    }
    public function index($request)
    {
        try {
            $class_room = $this->model->query()->with(['createdBy','transLocale'])->filter($request->query())->get();
            if ($class_room->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No class_room found', []);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'class_room retrieved successfully', ClassRoomResource::collection($class_room)->response()->getData(true));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No class_room found', $e->getMessage());
        }
    }
    public function store($request)
    {
        try {
            DB::beginTransaction();
            $data = $request->getData();
            $class_room = $this->model->create($data);
            DB::commit();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'class_room created successfully', new ClassRoomResource($class_room));
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No class_room found', $e->getMessage());
        }
    }

    public function update($local, $request, $model)
    {

        try {
            $data = $request->getData();
            $model->update($data);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'eduction updated successfully', new ClassRoomResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No class_room found', []);
        }
    }
    public function delete($local, $model)
    {
        try {
            $model->delete();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'class_room deleted successfully', []);
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No eduction found', []);
        }
    }


    public function show($local, $model)
    {
        try {
            $model->load('class_room');
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'class_room retrieved successfully', new ClassRoomResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No class_room found', []);
        }
    }
    public function showDeleted()
    {
        $class_room = $this->model->getAllDeleted();
        if ($class_room->isEmpty()) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No deleted class_room found', []);
        }
        return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Deleted class_room retrieved successfully', ClassRoomResource::collection($class_room));
    }
    public function restore($local, $id)
    {
        try {
            $this->model->restoreSoft($id);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'class_room restored successfully');
        } catch (Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No class_room found', []);
        }
    }

    public function forceDelete($local, $id)
    {
        try {
            $this->model->forceDeleteById($id);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'class_room force deleted successfully');
        } catch (Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No class_room found', []);
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
