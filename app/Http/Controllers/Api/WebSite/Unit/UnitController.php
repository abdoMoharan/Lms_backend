<?php
namespace App\Http\Controllers\Api\WebSite\Unit;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Unit\UnitResource;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
            $Unit = $this->model->query()->with(['transLocale', 'course'])->filter($request->query())->get();
            if ($Unit->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No Unit found', []);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Unit retrieved successfully', UnitResource::collection($Unit)->response()->getData(true));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Unit found', $e->getMessage());
        }
    }
    public function show($local, $id)
    {
        try {
            $model = $this->model->find($id);
            if (! $model) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'Unit not found', []);
            }
            $model->load(['transLocale', 'course']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Unit retrieved successfully', new UnitResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Unit found', []);
        }
    }

}
