<?php

namespace App\Http\Controllers\Api\Subject;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Subject\SubjectRepository;
use App\Http\Requests\Api\Subject\SubjectRequest;

class SubjectController extends Controller
{
    public SubjectRepository $repository;
    public function __construct(SubjectRepository $repository)
    {
        $this->repository = $repository;
    }
    public function index(Request $request)
{
        return $this->repository->index($request);
    }
    public function store(SubjectRequest $request)
    {
        return $this->repository->store($request);
    }
    public function update($local,SubjectRequest $request,Subject $model)
    {
        return $this->repository->update($local,$request,$model);
    }
    public function delete($local,Subject $model)
    {
        return $this->repository->delete($local,$model);
    }
    public function show($local,Subject $model)
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
