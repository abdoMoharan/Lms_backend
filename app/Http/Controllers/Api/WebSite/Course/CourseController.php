<?php
namespace App\Http\Controllers\Api\WebSite\Course;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Course\CourseResource;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
            $Course = $this->model->query()->with(['transLocale', 'subject', 'educationalStage', 'semesters', 'grade','groups'])->filter($request->query())->get();
            if ($Course->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No Course found', []);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Course retrieved successfully', CourseResource::collection($Course)->response()->getData(true));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Course found', $e->getMessage());
        }
    }
    public function show($locale, $id, $slug = null)
    {

        try {
            if ($slug) {
                $model = $this->model->whereHas('transLocale', function ($query) use ($slug, $locale) {
                    $query->where('slug', $slug)->where('locale', $locale);
                })->first();
            } else {
                $model = $this->model->find($id);
            }
            if (! $model) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'Course not found', []);
            }
            $model->load(['transLocale',  'subject', 'educationalStage', 'semesters', 'grade','groups']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Course retrieved successfully', new CourseResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No education stage found', $e->getMessage());
        }
    }
}
