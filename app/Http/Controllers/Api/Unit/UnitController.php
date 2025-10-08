<?php

namespace App\Http\Controllers\Api\Unit;

use App\Models\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Unit\UnitRepository;
use App\Http\Requests\Api\Unit\UnitRequest;

class UnitController extends Controller
{
    public UnitRepository $repository;
    public function __construct(UnitRepository $repository)
    {
        $this->repository = $repository;
    }
    public function index(Request $request)
{
        return $this->repository->index($request);
    }
    public function store(UnitRequest $request)
    {
        return $this->repository->store($request);
    }
    public function update($local,UnitRequest $request,Unit $model)
    {
        return $this->repository->update($local,$request,$model);
    }
    public function delete($local,Unit $model)
    {
        return $this->repository->delete($local,$model);
    }
    public function show($local,Unit $model)
    {
        return $this->repository->show($local,$model);
    }
    public function showDeleted()
    {
        return $this->repository->showDeleted();
    }
    public function restore($local,$id)
    {
        return $this->repository->restore($local,$id);
    }
    public function forceDelete($local,$id)
    {
        return $this->repository->forceDelete($local,$id);
    }
    public function multi_actions($local,Request $request)
    {
        return $this->repository->multi_actions($local,$request);
    }
}
