<?php

namespace App\Http\Controllers\Api\WebSite\Subject;
use App\Models\Subject;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Subject\SubjectResource;

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
            $Subject = $this->model->query()->with(['transLocale','educationalStage','semester','grade'])->filter($request->query())->get();
            if ($Subject->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No Subject found', []);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Subject retrieved successfully', SubjectResource::collection($Subject)->response()->getData(true));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Subject found', $e->getMessage());
        }
    }
    public function show($local, $model)
    {
        try {
            $model->load(['transLocale','educationalStage','semester','grade']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Subject retrieved successfully', new SubjectResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Subject found', []);
        }
    }

}
