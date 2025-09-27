<?php

namespace App\Http\Controllers\Api\WebSite\EducationalStage;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Models\EducationalStage;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\EducationalStage\EducationalStageResource;

class EducationalStageController extends Controller
{
    public EducationalStage $model;

    public function __construct(EducationalStage $model)
    {
        $this->model = $model;
    }
    public function index(Request $request)
    {
        try {
            $eduction_stage = $this->model->query()->with(['transLocale','grades'])->filter($request->query())->get();
            if ($eduction_stage->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No eduction_stage found', []);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'eduction_stage retrieved successfully', EducationalStageResource::collection($eduction_stage)->response()->getData(true));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No eduction_stage found', $e->getMessage());
        }
    }


    public function show($local, $model)
    {
        try {
            $model->load(['transLocale','grades']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'eduction_stage retrieved successfully', new EducationalStageResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No eduction_stage found', []);
        }
    }

}
