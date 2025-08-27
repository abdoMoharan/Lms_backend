<?php

namespace App\Http\Controllers\Api\ClassRoom;

use Illuminate\Http\Request;
use App\Models\ClassRoom;
use App\Http\Controllers\Controller;
use App\Interfaces\ClassRoom\ClassRoomInterface;
use App\Http\Requests\Api\ClassRoom\ClassRoomRequest;

class ClassRoomController extends Controller
{
    public ClassRoomInterface $interface;
    public function __construct(ClassRoomInterface $interface)
    {
        $this->interface = $interface;
    }
    public function index(Request $request)
    {
        return $this->interface->index($request);
    }
    public function store(ClassRoomRequest $request)
    {
        return $this->interface->store($request);
    }
    public function update($local,ClassRoomRequest $request,ClassRoom $model)
    {
        return $this->interface->update($local,$request,$model);
    }
    public function delete($local,ClassRoom $model)
    {
        return $this->interface->delete($local,$model);
    }
    public function show($local,ClassRoom $model)
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
