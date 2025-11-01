<?php
namespace App\Http\Controllers\Api\WebSite\Teacher;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public User $model;
    public function __construct(User $model)
    {
        $this->model = $model;
    }
    public function index(Request $request)
    {
        try {
            $Teacher = $this->model->query()->with(['profile', 'userEductionStage'])->where('user_type', 'teacher')->filter($request->query())->get();
            if ($Teacher->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No Teacher found', []);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Teacher retrieved successfully', UserResource::collection($Teacher)->response()->getData(true));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Teacher found', $e->getMessage());
        }
    }
    public function show($locale, $id)
    {

        try {
            $model = $this->model->where('id',$id)->where('user_type','teacher')->first();

            if (! $model) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'Teacher not found');
            }
            $model->load([ 'profile', 'userEductionStage']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Teacher retrieved successfully', new UserResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Teacher found', $e->getMessage());
        }
    }
}
