<?php

namespace App\Http\Controllers\Api\Answer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Answer\AnswerRequest;
use App\Interfaces\Answer\AnswerInterface;
use App\Models\Answer;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public AnswerInterface $interface;
    public function __construct(AnswerInterface $interface)
    {
        $this->interface = $interface;
    }
    public function index(Request $request)
    {
        return $this->interface->index($request);
    }
    public function store(AnswerRequest $request)
    {
        return $this->interface->store($request);
    }
    public function update($local,AnswerRequest $request,Answer $model)
    {
        return $this->interface->update($local,$request,$model);
    }
    public function delete($local,Answer $model)
    {
        return $this->interface->delete($local,$model);
    }
    public function show($local,Answer $model)
    {
        return $this->interface->show($local,$model);
    }
}
