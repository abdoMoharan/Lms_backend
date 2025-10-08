<?php

namespace App\Http\Controllers\Api\Answer;

use App\Models\Answer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Answer\AnswerRepository;
use App\Http\Requests\Api\Answer\AnswerRequest;

class AnswerController extends Controller
{
    public AnswerRepository $repository;
    public function __construct(AnswerRepository $repository)
    {
        $this->repository = $repository;
    }
    public function index(Request $request)
    {
        return $this->repository->index($request);
    }
    public function store(AnswerRequest $request)
    {
        return $this->repository->store($request);
    }
    public function update($local,AnswerRequest $request,Answer $model)
    {
        return $this->repository->update($local,$request,$model);
    }
    public function delete($local,Answer $model)
    {
        return $this->repository->delete($local,$model);
    }
    public function show($local,Answer $model)
    {
        return $this->repository->show($local,$model);
    }
}
