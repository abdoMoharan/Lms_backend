<?php

namespace App\Http\Controllers\Api\Subject;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\Subject\SubjectInterface;
use App\Http\Requests\Api\Subject\SubjectRequest;

class SubjectController extends Controller
{
    public SubjectInterface $interface;
    public function __construct(SubjectInterface $interface)
    {
        $this->interface = $interface;
    }
    public function index(Request $request)
{
        return $this->interface->index($request);
    }
    public function store(SubjectRequest $request)
    {
        return $this->interface->store($request);
    }
    public function update($local,SubjectRequest $request,Subject $model)
    {
        return $this->interface->update($local,$request,$model);
    }
    public function delete($local,Subject $model)
    {
        return $this->interface->delete($local,$model);
    }
    public function show($local,Subject $model)
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
