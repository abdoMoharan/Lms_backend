<?php

namespace App\Http\Controllers\Api\Grade;

use App\Models\Grade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\Grade\GradeInterface;
use App\Http\Requests\Api\Grade\GradeRequest;

class GradeController extends Controller
{
    public GradeInterface $interface;
    public function __construct(GradeInterface $interface)
    {
        $this->interface = $interface;
    }
    public function index(Request $request)
{
        return $this->interface->index($request);
    }
    public function store(GradeRequest $request)
    {
        return $this->interface->store($request);
    }
    public function update($local,GradeRequest $request,Grade $model)
    {
        return $this->interface->update($local,$request,$model);
    }
    public function delete($local,Grade $model)
    {
        return $this->interface->delete($local,$model);
    }
    public function show($local,Grade $model)
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
