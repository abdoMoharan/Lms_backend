<?php

namespace App\Http\Controllers\Api\Semester;

use App\Models\Semester;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Semester\SemesterRepository;
use App\Http\Requests\Api\Semester\SemesterRequest;

class SemesterController extends Controller
{
    public SemesterRepository $repository;
    public function __construct(SemesterRepository $repository)
    {
        $this->repository = $repository;
    }
    public function index(Request $request)
    {
        return $this->repository->index($request);
    }
    public function store(SemesterRequest $request)
    {
        return $this->repository->store($request);
    }
    public function update($local,SemesterRequest $request,Semester $model)
    {
        return $this->repository->update($local,$request,$model);
    }
    public function delete($local,Semester $model)
    {
        return $this->repository->delete($local,$model);
    }
    public function show($local,Semester $model)
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
