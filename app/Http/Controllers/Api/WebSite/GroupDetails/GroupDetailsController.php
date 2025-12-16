<?php
namespace App\Http\Controllers\Api\WebSite\GroupDetails;


use App\Models\Group;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Group\GroupResource;

class GroupDetailsController extends Controller
{


    public function show($locale, $id)
    {
        $model = Group::find($id);

        if (! $model) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'Group not found', []);
        }
        $model->load(['teacher','groupDays','groupDays','groupSession','course']);
        // $model->load(['course','teacher','groupDays','groupSession']);
        return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Group retrieved successfully', new GroupResource($model));
    }
}
