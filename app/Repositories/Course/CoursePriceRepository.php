<?php
namespace App\Repositories\Course;

use App\Models\CoursePrice;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Abstract\BaseRepository;
use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\Course\CoursePriceResource;

class CoursePriceRepository extends BaseRepository
{
    public CoursePrice $model;

    public function __construct(CoursePrice $model)
    {
        $this->model = $model;
    }
    public function index($request)
    {
        try {
            $model = $this->model->query()->with(['stage', 'grade', 'course'])->get();
            if ($model->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No course found', []);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'course price retrieved successfully', CoursePriceResource::collection($model)->response()->getData(true));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No course found', $e->getMessage());
        }
    }
    public function store($request)
    {
        try {
            DB::beginTransaction();
            $data  = $request->getData();
            $model = $this->model->create($data);

            $model->load(['stage', 'grade', 'course']);
            DB::commit();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'course price created successfully', new CoursePriceResource($model));
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No course price found', $e->getMessage());
        }
    }

    public function update($local, $request, $model)
    {
        try {
            $data = $request->getData();
            $model->update($data);
            $model->load(['stage', 'grade', 'course']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'course price updated successfully', new CoursePriceResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No course price found', $e->getMessage());
        }
    }
    public function delete($local, $model)
    {
        try {
            $model->delete();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'course price deleted successfully', []);
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No eduction found', []);
        }
    }

    public function show($local, $model)
    {
        try {
            $model->load(['stage', 'grade', 'course']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'course price retrieved successfully', new CoursePriceResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No course price found', []);
        }
    }

}
