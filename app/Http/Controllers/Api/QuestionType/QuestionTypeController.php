<?php

namespace App\Http\Controllers\Api\QuestionType;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\QuestionType\QuestionTypeRequest;
use App\Interfaces\QuestionType\QuestionTypeInterface;
use App\Models\QuestionType;
use Illuminate\Http\Request;

class QuestionTypeController extends Controller
{
    public QuestionTypeInterface $interface;
    public function __construct(QuestionTypeInterface $interface)
    {
        $this->interface = $interface;
    }
    public function index(Request $request)
    {
        return $this->interface->index($request);
    }
    public function store(QuestionTypeRequest $request)
    {
        return $this->interface->store($request);
    }
    public function update($local,QuestionTypeRequest $request,QuestionType $model)
    {
        return $this->interface->update($local,$request,$model);
    }
    public function delete($local,QuestionType $model)
    {
        return $this->interface->delete($local,$model);
    }
    public function show($local,QuestionType $model)
    {
        return $this->interface->show($local,$model);
    }
    public function showDeleted()
    {
        return $this->interface->showDeleted();
    }
    public function restore($local,$id)
    {
        return $this->interface->restore($local,$id);
    }
    public function forceDelete($local,$id)
    {
        return $this->interface->forceDelete($local,$id);
    }
    public function multi_actions($local,Request $request)
    {
        return $this->interface->multi_actions($local,$request);
    }
}
