<?php

namespace App\Http\Controllers\Api\Grade;

use App\Models\Grade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Grade\GradeRepository;
use App\Http\Requests\Api\Grade\GradeRequest;

class GradeController extends Controller
{
    public GradeRepository $repository;
    public function __construct(GradeRepository $repository)
    {
        $this->repository = $repository;
    }
    public function index(Request $request)
{
        return $this->repository->index($request);
    }
    public function store(GradeRequest $request)
    {
        return $this->repository->store($request);
    }
    public function update($local,GradeRequest $request,Grade $model)
    {
        return $this->repository->update($local,$request,$model);
    }
    public function delete($local,Grade $model)
    {
        return $this->repository->delete($local,$model);
    }
    public function show($local,Grade $model)
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
