<?php
namespace App\Http\Controllers\Api\Website\Group;

use App\Models\Group;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Group\GroupResource;

class GroupController extends Controller
{
    public function show($locale, $id)
    {
        $model = Group::find($id);
        if (! $model) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'Group not found', []);
        }
        $model->load(['transLocale', 'course','teacher','groupDays','groupSession']);
        return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Group retrieved successfully', new GroupResource($model));
    }
}
