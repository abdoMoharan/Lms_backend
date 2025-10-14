<?php
namespace App\Repositories\Group;

use App\Helpers\ApiResponse;
use App\Http\Abstract\BaseRepository;
use App\Http\Resources\Group\GroupResource;
use App\Models\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class GroupRepository extends BaseRepository
{
    public Group $model;

    public function __construct(Group $model)
    {
        $this->model = $model;
    }
    public function index($request)
    {
        try {
            $Groups = $this->model->query()->with(['teacher', 'course'])->filter($request->query())->get();
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
            $data  = $request->getData();
            $model = $this->model->create($data);
            $model->load(['teacher', 'course']);
            DB::commit();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Groups created successfully', new GroupResource($model));
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Groups found', $e->getMessage());
        }
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
            $model->load(['teacher', 'course']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Groups retrieved successfully', new GroupResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Groups found', []);
        }
    }

}
