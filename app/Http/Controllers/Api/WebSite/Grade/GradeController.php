<?php
namespace App\Http\Controllers\Api\WebSite\Grade;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Grade\GradeResource;
use App\Models\Grade;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public Grade $model;
    public function __construct(Grade $model)
    {
        $this->model = $model;
    }
    public function index(Request $request)
    {
        try {
            $Grade = $this->model->query()->with(['transLocale', 'educationalStage'])->filter($request->query())->get();
            if ($Grade->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No Grade found', []);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Grade retrieved successfully', GradeResource::collection($Grade)->response()->getData(true));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Grade found', $e->getMessage());
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
                return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'Grade not found', []);
            }
            $model->load(['transLocale', 'educationalStage,courses']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Grade retrieved successfully', new GradeResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No education stage found', $e->getMessage());
        }
    }
}
