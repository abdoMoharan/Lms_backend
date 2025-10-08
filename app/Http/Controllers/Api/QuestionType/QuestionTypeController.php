<?php

namespace App\Http\Controllers\Api\QuestionType;

use App\Models\QuestionType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\QuestionType\QuestionTypeRepository;
use App\Http\Requests\Api\QuestionType\QuestionTypeRequest;

class QuestionTypeController extends Controller
{
    public QuestionTypeRepository $repository;
    public function __construct(QuestionTypeRepository $repository)
    {
        $this->repository = $repository;
    }
    public function index(Request $request)
    {
        return $this->repository->index($request);
    }
    public function store(QuestionTypeRequest $request)
    {
        return $this->repository->store($request);
    }
    public function update($local,QuestionTypeRequest $request,QuestionType $model)
    {
        return $this->repository->update($local,$request,$model);
    }
    public function delete($local,QuestionType $model)
    {
        return $this->repository->delete($local,$model);
    }
    public function show($local,QuestionType $model)
    {
        return $this->repository->show($local,$model);
    }
    public function showDeleted()
    {
        return $this->repository->showDeleted();
    }
    public function restore($local,$id)
    {
        return $this->repository->restore($local,$id);
    }
    public function forceDelete($local,$id)
    {
        return $this->repository->forceDelete($local,$id);
    }
    public function multi_actions($local,Request $request)
    {
        return $this->repository->multi_actions($local,$request);
    }
}
