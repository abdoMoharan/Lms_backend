<?php

namespace App\Http\Controllers\Api\WebSite\Unit;
use App\Models\Unit;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Unit\UnitResource;

class UnitController extends Controller
{
    public Unit $model;

    public function __construct(Unit $model)
    {
        $this->model = $model;
    }
    public function index(Request $request)
    {
        try {
            $Unit = $this->model->query()->with(['transLocale','course'])->filter($request->query())->get();
            if ($Unit->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No Unit found', []);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Unit retrieved successfully', UnitResource::collection($Unit)->response()->getData(true));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Unit found', $e->getMessage());
        }
    }
    public function show($local, $model)
    {
        try {
            $model->load(['transLocale','course']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Unit retrieved successfully', new UnitResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Unit found', []);
        }
    }

}
