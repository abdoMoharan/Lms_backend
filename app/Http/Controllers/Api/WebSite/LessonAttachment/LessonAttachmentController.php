<?php
namespace App\Http\Controllers\Api\WebSite\LessonAttachment;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Lessons\AttachmentResource;
use App\Models\LessonsAttachment;
use Illuminate\Http\JsonResponse;

class LessonAttachmentController extends Controller
{
    public LessonsAttachment $model;

    public function __construct(LessonsAttachment $model)
    {
        $this->model = $model;
    }
    public function show($local, $model)
    {
        $model = $this->model->find($model);
        if (!$model) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No attachment retrieved found', []);
        }
        try {
            $model->load(['teacher', 'group_session']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'attachment retrieved successfully', new AttachmentResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No attachment found', []);
        }
    }
}
