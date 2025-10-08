<?php

namespace App\Http\Controllers\Api\EducationalStage;

use Illuminate\Http\Request;
use App\Models\EducationalStage;
use App\Http\Controllers\Controller;
use App\Repositories\EducationalStage\EducationalStageRepository;
use App\Http\Requests\Api\EducationalStage\EducationalStageRequest;

class EducationalStageController extends Controller
{
    public EducationalStageRepository $repository;
    public function __construct(EducationalStageRepository $repository)
    {
        $this->repository = $repository;
    }
    public function index(Request $request)
    {
        return $this->repository->index($request);
    }
    public function store(EducationalStageRequest $request)
    {
        return $this->repository->store($request);
    }
    public function update($local,EducationalStageRequest $request,EducationalStage $model)
    {
        return $this->repository->update($local,$request,$model);
    }
    public function delete($local,EducationalStage $model)
    {
        return $this->repository->delete($local,$model);
    }
    public function show($local,EducationalStage $model)
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
