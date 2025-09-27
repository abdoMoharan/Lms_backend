<?php

namespace App\Http\Controllers\Api\WebSite\Semester;

use App\Models\Semester;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Semester\SemesterResource;

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
    public function show($local, $model)
    {
        try {
            $model->load('transLocale');
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'semester retrieved successfully', new SemesterResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No semester found', []);
        }
    }

}
