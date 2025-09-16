<?php

namespace App\Http\Controllers\Api\Question;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Question\QuestionRequest;
use App\Interfaces\Question\QuestionInterface;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public QuestionInterface $interface;
    public function __construct(QuestionInterface $interface)
    {
        $this->interface = $interface;
    }
    public function index(Request $request)
    {
        return $this->interface->index($request);
    }
    public function store(QuestionRequest $request)
    {
        return $this->interface->store($request);
    }
    public function update($local,QuestionRequest $request,Question $model)
    {
        return $this->interface->update($local,$request,$model);
    }
    public function delete($local,Question $model)
    {
        return $this->interface->delete($local,$model);
    }
    public function show($local,Question $model)
    {
        return $this->interface->show($local,$model);
    }
}
