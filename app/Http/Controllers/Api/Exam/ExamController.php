<?php
namespace App\Http\Controllers\Api\Exam;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Exam\ExamRequest;
use App\Models\Exam;
use App\Repositories\Exam\ExamRepository;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public ExamRepository $repository;
    public function __construct(ExamRepository $repository)
    {
        $this->repository = $repository;
    }
    public function index(Request $request)
    {
        return $this->repository->index($request);
    }
    public function store(ExamRequest $request)
    {
        return $this->repository->store($request);
    }
    public function update($local, ExamRequest $request, Exam $model)
    {
        return $this->repository->update($local, $request, $model);
    }
    public function delete($local, Exam $model)
    {
        return $this->repository->delete($local, $model);
    }
    public function show(Exam $model)
    {
        return $this->repository->show($model);
    }
    public function showDeleted()
    {
        return $this->repository->showDeleted();
    }
    public function restore($local, $id)
    {
        return $this->repository->restore($local, $id);
    }
    public function forceDelete($local, $id)
    {
        return $this->repository->forceDelete($local, $id);
    }
    public function multi_actions($local, Request $request)
    {
        return $this->repository->multi_actions($local, $request);
    }
}
