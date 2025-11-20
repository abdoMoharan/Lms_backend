<?php

namespace App\Http\Controllers\Api\Course;

use App\Models\CoursePrice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Course\CoursePriceRepository;
use App\Http\Requests\Api\Course\CoursePriceRequest;

class CoursePriceController extends Controller
{
    public CoursePriceRepository $repository;
    public function __construct(CoursePriceRepository $repository)
    {
        $this->repository = $repository;
    }
    public function index(Request $request)
    {
        return $this->repository->index($request);
    }
    public function store(CoursePriceRequest $request)
    {
        return $this->repository->store($request);
    }
    public function update($local,CoursePriceRequest $request,CoursePrice $model)
    {
        return $this->repository->update($local,$request,$model);
    }
    public function delete($local,CoursePrice $model)
    {
        return $this->repository->delete($local,$model);
    }
    public function show($local,CoursePrice $model)
    {
        return $this->repository->show($local,$model);
    }
}
