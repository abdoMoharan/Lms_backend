<?php
namespace App\Repositories\Answer;

use App\Helpers\ApiResponse;
use App\Http\Resources\Answer\AnswerResource;
use App\Interfaces\Answer\AnswerInterface;
use App\Models\Answer;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AnswerRepository implements AnswerInterface
{
    public Answer $model;

    public function __construct(Answer $model)
    {
        $this->model = $model;
    }
    public function index($request)
    {
        try {
            $model = $this->model->query()->with([ 'question'])->filter($request->query())->get();
            if ($model->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No Answer found', []);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Answer retrieved successfully', AnswerResource::collection($model)->response()->getData(true));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Answer found', $e->getMessage());
        }
    }
    public function store($request)
    {
        try {
            DB::beginTransaction();
            $data  = $request->getData();
            $model = $this->model->create($data);
            $model->load(['question']);
            DB::commit();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Answer created successfully', new AnswerResource($model));
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Answer found', $e->getMessage());
        }
    }

    public function update($local, $request, $model)
    {

        try {
            $data = $request->getData();
            $model->update($data);
            $model->load(['question']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'eduction updated successfully', new AnswerResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Answer found', []);
        }
    }
    public function delete($local, $model)
    {
        try {
            $model->delete();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Answer deleted successfully', []);
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No eduction found', []);
        }
    }

    public function show($local, $model)
    {
        try {
            $model->load([ 'question']);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Answer retrieved successfully', new AnswerResource($model));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No Answer found', []);
        }
    }
}
