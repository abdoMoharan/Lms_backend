<?php

namespace App\Http\Controllers\Api\Question;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Question\QuestionRepository;
use App\Http\Requests\Api\Question\QuestionRequest;

class QuestionController extends Controller
{
    public QuestionRepository $repository;
    public function __construct(QuestionRepository $repository)
    {
        $this->repository = $repository;
    }
    public function index(Request $request)
    {
        return $this->repository->index($request);
    }
    public function store(QuestionRequest $request)
    {
        return $this->repository->store($request);
    }
    public function update($local,QuestionRequest $request,Question $model)
    {
        return $this->repository->update($local,$request,$model);
    }
    public function delete($local,Question $model)
    {
        return $this->repository->delete($local,$model);
    }
    public function show($local,Question $model)
    {
        return $this->repository->show($local,$model);
    }
}
