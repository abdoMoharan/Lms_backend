<?php

namespace App\Http\Controllers\Api\Website;

use App\Models\Group;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Group\GroupResource;

class DetailsGroupController extends Controller
{
    public function show($id)
    {
        $model = Group::find($id);

        if (! $model) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'Group not found', []);
        }
        $model->load(['course','teacher','groupDays','groupSession']);
        return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Group retrieved successfully', new GroupResource($model));
    }
}
