<?php
namespace App\Http\Controllers\Api\Lessons;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Lessons\LessonsRequest;
use App\Interfaces\Lessons\LessonInterface;
use App\Models\Lessons;
use Illuminate\Http\Request;

class LessonsController extends Controller
{
    public LessonInterface $interface;
    public function __construct(LessonInterface $interface)
    {
        $this->interface = $interface;
    }
    public function index(Request $request)
    {
        return $this->interface->index($request);
    }
    public function store(LessonsRequest $request)
    {
        return $this->interface->store($request);
    }
    public function update($local, LessonsRequest $request, Lessons $model)
    {

        return $this->interface->update($local, $request, $model);
    }
    public function delete($local, Lessons $model)
    {

        return $this->interface->delete($local, $model);
    }
    public function show($local, Lessons $model)
    {
        return $this->interface->show($local, $model);
    }
    public function showDeleted()
    {
        return $this->interface->showDeleted();
    }
    public function restore($local, $id)
    {
        return $this->interface->restore($local, $id);
    }
    public function forceDelete($local, $id)
    {
        return $this->interface->forceDelete($local, $id);
    }
    public function multi_actions($local, Request $request)
    {
        return $this->interface->multi_actions($local, $request);
    }

    public function updateAttachment($local, Lessons $model, Request $request)
    {
        return $this->interface->updateAttachment($local, $model, $request);
    }
    public function deleteAttachment($local, Lessons $model)
    {
        return $this->interface->deleteAttachment($local, $model);
    }

}
