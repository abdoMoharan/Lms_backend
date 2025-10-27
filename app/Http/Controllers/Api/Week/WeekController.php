<?php
namespace App\Http\Controllers\Api\Week;


use App\Models\Week;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Group\WeekResource;

class WeekController extends Controller
{
    public Week $model;
    public function __construct(Week $model)
    {
        $this->model = $model;
    }
    public function index(Request $request)
    {
        try {
            $Week = $this->model->query()->get();
            if ($Week->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No Week found', []);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Week retrieved successfully', WeekResource::collection($Week)->response()->getData(true));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Week found', $e->getMessage());
        }
    }


 public function show($locale, $id)
    {
        try {
              $model = $this->model->find($id);
            if (! $model) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'Week not found', []);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Week retrieved successfully', new WeekResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Week  found', $e->getMessage());
        }
    }
}
