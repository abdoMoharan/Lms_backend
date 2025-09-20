<?php
namespace App\Repositories\Question;

use App\Helpers\ApiResponse;
use App\Http\Resources\Question\QuestionResource;
use App\Interfaces\Question\QuestionInterface;
use App\Models\Question;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class QuestionRepository implements QuestionInterface
{
    public Question $model;

    public function __construct(Question $model)
    {
        $this->model = $model;
    }
    public function index($request)
    {
        try {
            $model = $this->model->query()->with(['createdBy', 'exam','question_type'])->filter($request->query())->get();
            if ($model->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No Question found', []);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Question retrieved successfully', QuestionResource::collection($model)->response()->getData(true));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Question found', $e->getMessage());
        }
    }
    public function store($request)
    {
        try {
            DB::beginTransaction();
            $data  = $request->getData();
            $model = $this->model->create($data);
            $model->load(['createdBy','exam','question_type']);
            DB::commit();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Question created successfully', new QuestionResource($model));
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Question found', $e->getMessage());
        }
    }

    public function update($local, $request, $model)
    {

        try {
            $data = $request->getData();
            $model->update($data);
            $model->load(['createdBy','exam','question_type']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'eduction updated successfully', new QuestionResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Question found', []);
        }
    }
    public function delete($local, $model)
    {
        try {
            $model->delete();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Question deleted successfully', []);
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No eduction found', []);
        }
    }

    public function show($local, $model)
    {
        try {
            $model->load([ 'createdBy','exam','question_type']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Question retrieved successfully', new QuestionResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Question found', []);
        }
    }



}
