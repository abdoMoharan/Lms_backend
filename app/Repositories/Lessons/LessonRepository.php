<?php
namespace App\Repositories\Lessons;

use Exception;
use App\Models\Lessons;
use App\Helpers\ApiResponse;
use App\Models\LessonsAttachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Abstract\BaseRepository;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\Lessons\LessonInterface;
use App\Http\Resources\Lessons\LessonsResource;

class LessonRepository extends BaseRepository
{
    public Lessons $model;

    public function __construct(Lessons $model)
    {
        $this->model = $model;
    }
    public function index($request)
    {
        try {
            $model = $this->model->query()->with(['createdBy', 'transLocale', 'unit'])->filter($request->query())->get();
            if ($model->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No lesson found', []);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'lesson retrieved successfully', LessonsResource::collection($model)->response()->getData(true));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No lesson found', $e->getMessage());
        }
    }
    public function store($request)
    {
        try {
            DB::beginTransaction();
            $data = $request->getData();
            // dd($data);
            $model = $this->model->create($data);
            if ($request->attachment) {
                $model->attachments()->createMany($request->attachment);
            }
            $model->load(['createdBy', 'transLocale', 'unit']);
            DB::commit();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'lesson created successfully', new LessonsResource($model));
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No lesson found', $e->getMessage());
        }
    }

    public function update($local, $request, $model)
    {
        // تحقق من وجود الـ Lesson باستخدام الـ ID
        if (! $model) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'Lesson not found', []);
        }
        try {
            DB::beginTransaction();
            $data = $request->getData();
            if ($request->hasFile('cover_image')) {
                Storage::disk('attachment')->delete($model->cover_image);

            }
            $model->update($data);

            $model->load(['createdBy', 'transLocale', 'unit']);
            DB::commit();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'lesson updated successfully', new LessonsResource($model));
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No lesson found', $e->getMessage());
        }
    }
    public function delete($local, $model)
    {
        try {
            $model->delete();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'lesson deleted successfully', []);
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No lesson found', []);
        }
    }

    public function show($local, $model)
    {
        try {
            $model->load(['createdBy', 'trans', 'unit']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'lesson retrieved successfully', new LessonsResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No lesson found', []);
        }
    }
    public function showDeleted()
    {
        $model = $this->model->getAllDeleted();
        if ($model->isEmpty()) {
            $model->load(['createdBy', 'transLocale', 'unit']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No deleted lesson found', []);
        }
        return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Deleted lesson retrieved successfully', LessonsResource::collection($model));
    }
    public function restore($local, $id)
    {
        try {
            $this->model->restoreSoft($id);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'lesson restored successfully');
        } catch (Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No lesson found', []);
        }
    }

    public function forceDelete($local, $id)
    {

        try {
            $this->model->forceDeleteById($id);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'lesson force deleted successfully');
        } catch (Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No lesson found', []);
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

    public function updateAttachment($local, $model, $request)
    {
        $data = $request->validated([
            'file'         => 'nullable|mimes:pdf,docx,doc,xls,xlsx,ppt,pptx,zip,rar',
            'type'         => 'nullable|in:upload_video,youtube_link,vimeo_link',
            'video_upload' => 'nullable|mimes:mp4,mov,avi,wmv',
            'link'         => 'nullable|active_url',
            'image'        => 'nullable|mimes:jpg,jpeg,png',
        ]);
        $data['lesson_id'] = $model->id;
        try {
            DB::beginTransaction();

            $attachment = LessonsAttachment::where('lesson_id', $model->id)->first();
            if ($attachment) {
                if ($data['type'] == 'upload_video' || $data['type'] == 'youtube_link' || $data['type'] == 'vimeo_link') {
                    $data['link'] = null;
                }
                if ($request->hasFile('file')) {
                    Storage::disk('attachment')->delete($attachment->file);
                }
                if ($request->hasFile('video_upload')) {
                    Storage::disk('attachment')->delete($attachment->video_upload);
                }
                if ($request->hasFile('video_upload')) {
                    Storage::disk('attachment')->delete($attachment->video_upload);
                }
            }
            $attachment->update($data);
            DB::commit();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'attachment updated successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No attachment found', []);
        }
    }
    public function deleteAttachment($local, $model)
    {
        try {
            DB::beginTransaction();
            $attachment = LessonsAttachment::where('lesson_id', $model->id)->first();
            if ($attachment) {
                if ($attachment->file) {
                    Storage::disk('attachment')->delete($attachment->file);
                }
                if ($attachment->video_upload) {
                    Storage::disk('attachment')->delete($attachment->video_upload);
                }
                if ($attachment->image) {
                    Storage::disk('attachment')->delete($attachment->image);
                }
                $attachment->delete();
            }
            DB::commit();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'attachment deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No attachment found', []);
        }
    }
}
