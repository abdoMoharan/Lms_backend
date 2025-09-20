<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Course\CourseRequest;
use App\Interfaces\Course\CourseInterface;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public CourseInterface $interface;
    public function __construct(CourseInterface $interface)
    {
        $this->interface = $interface;
    }
    public function index(Request $request)
    {
        return $this->interface->index($request);
    }
    public function store(CourseRequest $request)
    {
        return $this->interface->store($request);
    }
    public function update($local,CourseRequest $request,Course $model)
    {
        return $this->interface->update($local,$request,$model);
    }
    public function delete($local,Course $model)
    {
        return $this->interface->delete($local,$model);
    }
    public function show($local,Course $model)
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
