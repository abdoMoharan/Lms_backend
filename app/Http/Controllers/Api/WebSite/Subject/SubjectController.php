<?php
namespace App\Http\Controllers\Api\WebSite\Subject;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Subject\SubjectResource;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public Subject $model;
    public function __construct(Subject $model)
    {
        $this->model = $model;
    }
    public function index(Request $request)
    {
        try {
            $Subject = $this->model->query()->with(['transLocale', 'educationalStage', 'semesters', 'grade'])->filter($request->query())->get();
            if ($Subject->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No Subject found', []);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Subject retrieved successfully', SubjectResource::collection($Subject)->response()->getData(true));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Subject found', $e->getMessage());
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
                return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'Subject not found', []);
            }
          $model->load(['transLocale', 'educationalStage', 'semesters', 'grade','courses']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Subject retrieved successfully', new SubjectResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Subject  found', $e->getMessage());
        }
    }
}
