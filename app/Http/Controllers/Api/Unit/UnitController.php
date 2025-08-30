<?php

namespace App\Http\Controllers\Api\Unit;

use App\Models\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\Unit\UnitInterface;
use App\Http\Requests\Api\Unit\UnitRequest;

class UnitController extends Controller
{
    public UnitInterface $interface;
    public function __construct(UnitInterface $interface)
    {
        $this->interface = $interface;
    }
    public function index(Request $request)
{
        return $this->interface->index($request);
    }
    public function store(UnitRequest $request)
    {
        return $this->interface->store($request);
    }
    public function update($local,UnitRequest $request,Unit $model)
    {
        return $this->interface->update($local,$request,$model);
    }
    public function delete($local,Unit $model)
    {
        return $this->interface->delete($local,$model);
    }
    public function show($local,Unit $model)
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
