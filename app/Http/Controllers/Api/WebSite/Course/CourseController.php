<?php

namespace App\Http\Controllers\Api\WebSite\Course;
use App\Models\Course;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Course\CourseResource;

class CourseController extends Controller
{
    public Course $model;
    public function __construct(Course $model)
    {
        $this->model = $model;
    }
    public function index(Request $request)
    {
        try {
            $Course = $this->model->query()->with(['transLocale','teacher','subject'])->filter($request->query())->get();
            if ($Course->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No Course found', []);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Course retrieved successfully', CourseResource::collection($Course)->response()->getData(true));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Course found', $e->getMessage());
        }
    }
    public function show($local, $model)
    {
        try {
            $model->load(['transLocale','teacher','subject']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Course retrieved successfully', new CourseResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Course found', []);
        }
    }

}
