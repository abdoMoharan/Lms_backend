<?php

namespace App\Http\Controllers\Api\Course;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Course\CourseRepository;
use App\Http\Requests\Api\Course\CourseRequest;

class CourseController extends Controller
{
    public CourseRepository $repository;
    public function __construct(CourseRepository $repository)
    {
        $this->repository = $repository;
    }
    public function index(Request $request)
    {
        return $this->repository->index($request);
    }
    public function store(CourseRequest $request)
    {
        return $this->repository->store($request);
    }
    public function update($local,CourseRequest $request,Course $model)
    {
        return $this->repository->update($local,$request,$model);
    }
    public function delete($local,Course $model)
    {
        return $this->repository->delete($local,$model);
    }
    public function show($local,Course $model)
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
