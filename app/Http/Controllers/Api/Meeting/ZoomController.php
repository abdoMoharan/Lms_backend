<?php
namespace App\Http\Controllers\Api\Meeting;

use App\Models\MeetingZoom;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Meeting\ZoomResource;

class ZoomController extends Controller
{
    public MeetingZoom $model;
    public function __construct(MeetingZoom $model)
    {
        $this->model = $model;
    }
    public function index(Request $request)
    {
        try {
            $meeting = $this->model->query()->get();
            if ($meeting->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, ['No meeting found']);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'meeting retrieved successfully', ZoomResource::collection($meeting)->response()->getData(true));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, ['No meeting found'], $e->getMessage());
        }
    }

    public function show($local, MeetingZoom $model)
    {
        $model->load('group_session');
        return ApiResponse::apiResponse(JsonResponse::HTTP_OK, ['meeting retrieved successfully'], new ZoomResource($model));
    }
}
