<?php

namespace App\Http\Controllers\Api\Chapter;

use App\Models\Chapter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\Chapter\ChapterInterface;
use App\Http\Requests\Api\Chapter\ChapterRequest;

class ChapterController extends Controller
{
    public ChapterInterface $interface;
    public function __construct(ChapterInterface $interface)
    {
        $this->interface = $interface;
    }
    public function index(Request $request)
{
        return $this->interface->index($request);
    }
    public function store(ChapterRequest $request)
    {
        return $this->interface->store($request);
    }
    public function update($local,ChapterRequest $request,Chapter $model)
    {
        return $this->interface->update($local,$request,$model);
    }
    public function delete($local,Chapter $model)
    {
        return $this->interface->delete($local,$model);
    }
    public function show($local,Chapter $model)
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
