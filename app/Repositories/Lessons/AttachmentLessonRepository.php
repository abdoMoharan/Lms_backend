<?php
namespace App\Repositories\Lessons;

use Exception;
use App\Models\Lessons;
use App\Helpers\ApiResponse;
use App\Models\LessonsAttachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Lessons\AttachmentResource;
use App\Interfaces\Lessons\AttachmentLessonInterface;

class AttachmentLessonRepository implements AttachmentLessonInterface
{
    public LessonsAttachment $model;


    public function __construct(LessonsAttachment $model)
    {
        $this->model = $model;
    }
    public function index($request)
    {
        try {
            $model = $this->model->query()->with('lesson')->filter($request->query())->get();
            if ($model->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No attachment found', []);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'attachment retrieved successfully', AttachmentResource::collection($model)->response()->getData(true));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No attachment found', $e->getMessage());
        }
    }
    public function store($request)
    {
        try {
            DB::beginTransaction();
            $data = $request->getData();
            $model = $this->model->create($data);
            $model->load(['lesson']);
            DB::commit();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'attachment created successfully', new AttachmentResource($model));
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No attachment found', $e->getMessage());
        }
    }

    public function update($local, $request, $model)
    {
        if (! $model) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'Attachment not found', []);
        }
        try {
            DB::beginTransaction();
            $data = $request->getData();
            if ($request->hasFile('cover_image')) {
                Storage::disk('attachment')->delete($model->cover_image);
            }
            if ($request->hasFile('file')) {
                Storage::disk('attachment')->delete($model->file);
            }
            if ($request->hasFile('video_upload')) {
                Storage::disk('attachment')->delete($model->video_upload);
            }
            if ($request->hasFile('image')) {
                Storage::disk('attachment')->delete($model->image);
            }
            $model->update($data);
            $model->load(['lesson']);
            DB::commit();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'attachment updated successfully', new AttachmentResource($model));
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No attachment found', $e->getMessage());
        }
    }
    public function delete($local, $model)
    {
        try {
            if ($model->file) {
                Storage::disk('attachment')->delete($model->file);
            }
            if ($model->video_upload) {
                Storage::disk('attachment')->delete($model->video_upload);
            }
            if ($model->image) {
                Storage::disk('attachment')->delete($model->image);
            }
            $model->delete();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'attachment deleted successfully', []);
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No attachment found', []);
        }
    }

    public function show($local, $model)
    {
        try {
            $model->load(['lesson']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'attachment retrieved successfully', new AttachmentResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No attachment found', []);
        }
    }
    public function multi_actions($local, $request)
    {
        $data = $request->validate([
            'type'    => 'required',
            'records' => 'required|array',
        ]);

        switch ($data['type']) {
            case 'delete':
                $models = $this->model->findMany($request['records']);
                foreach ($models as $item) {
                    $item->delete();
                }
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'deleted successfully');
                break;
            case 'status_active':
                $models = $this->model->findMany($request['records']);
                foreach ($models as $item) {
                    $item->update(['status' => 1]);
                }
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'update status  successfully');
                break;
            case 'status_inactive':
                $models = $this->model->findMany($request['records']);
                foreach ($models as $item) {
                    $item->update(['status' => 0]);
                }
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'update status  successfully');
                break;
            case 'restore':
                $models = $this->model->onlyTrashed()->findMany($request['records']);
                foreach ($models as $item) {
                    $item->restore();
                }
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Records restored successfully');
                break;
            case 'force-delete':
                $models = $this->model->onlyTrashed()->findMany($request['records']);
                foreach ($models as $item) {
                    $item->forceDelete();
                }
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Records permanently deleted');
                break;
            default:
                return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No  found', []);
                break;
        }
    }
}
