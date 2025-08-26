<?php

namespace App\Http\Controllers\Api\EducationalStage;

use Illuminate\Http\Request;
use App\Models\EducationalStage;
use App\Http\Controllers\Controller;
use App\Interfaces\EducationalStage\EducationalStageInterface;
use App\Http\Requests\Api\EducationalStage\EducationalStageRequest;

class EducationalStageController extends Controller
{
    public EducationalStageInterface $interface;
    public function __construct(EducationalStageInterface $interface)
    {
        $this->interface = $interface;
    }

    public function index(Request $request)
    {
        return $this->interface->index($request);
    }

    public function store(EducationalStageRequest $request)
    {
        return $this->interface->store($request);
    }

    public function update($local,EducationalStageRequest $request,EducationalStage $model)
    {
        return $this->interface->update($local,$request,$model);
    }
    public function delete($local,EducationalStage $model)
    {

        return $this->interface->delete($local,$model);
    }
    public function show($local,EducationalStage $model)
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
