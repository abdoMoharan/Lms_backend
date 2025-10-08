<?php
namespace App\Http\Controllers\Api\Lessons;

use App\Models\Lessons;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Lessons\LessonRepository;
use App\Http\Requests\Api\Lessons\LessonsRequest;

class LessonsController extends Controller
{
    public LessonRepository $repository;
    public function __construct(LessonRepository $repository)
    {
        $this->repository = $repository;
    }
    public function index(Request $request)
    {
        return $this->repository->index($request);
    }
    public function store(LessonsRequest $request)
    {
        return $this->repository->store($request);
    }
    public function update($local, LessonsRequest $request, Lessons $model)
    {

        return $this->repository->update($local, $request, $model);
    }
    public function delete($local, Lessons $model)
    {

        return $this->repository->delete($local, $model);
    }
    public function show($local, Lessons $model)
    {
        return $this->repository->show($local, $model);
    }
    public function showDeleted()
    {
        return $this->repository->showDeleted();
    }
    public function restore($local, $id)
    {
        return $this->repository->restore($local, $id);
    }
    public function forceDelete($local, $id)
    {
        return $this->repository->forceDelete($local, $id);
    }
    public function multi_actions($local, Request $request)
    {
        return $this->repository->multi_actions($local, $request);
    }

    public function updateAttachment($local, Lessons $model, Request $request)
    {
        return $this->repository->updateAttachment($local, $model, $request);
    }
    public function deleteAttachment($local, Lessons $model)
    {
        return $this->repository->deleteAttachment($local, $model);
    }

}
