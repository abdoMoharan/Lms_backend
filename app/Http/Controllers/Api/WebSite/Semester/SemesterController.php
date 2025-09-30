<?php
namespace App\Http\Controllers\Api\WebSite\Semester;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Semester\SemesterResource;
use App\Models\Semester;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public Semester $model;
    public function __construct(Semester $model)
    {
        $this->model = $model;
    }
    public function index(Request $request)
    {
        try {
            $semester = $this->model->query()->with('transLocale')->filter($request->query())->get();
            if ($semester->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No semester found', []);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'semester retrieved successfully', SemesterResource::collection($semester)->response()->getData(true));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No semester found', $e->getMessage());
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
                return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'semester stage not found', []);
            }
            $model->load('transLocale');
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'semester stage retrieved successfully', new SemesterResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No education stage found', $e->getMessage());
        }
    }

}
