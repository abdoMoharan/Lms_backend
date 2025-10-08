<?php

namespace App\Http\Controllers\Api\Lessons;

use Illuminate\Http\Request;
use App\Models\LessonsAttachment;
use App\Http\Controllers\Controller;
use App\Repositories\Lessons\AttachmentLessonRepository;
use App\Http\Requests\Api\Lessons\AttachmentLessonRequest;

class AttachmentLessonController extends Controller
{
    public AttachmentLessonRepository $repository;

    public function __construct(AttachmentLessonRepository $repository)
    {
        $this->repository = $repository;
    }
    public function index(Request $request)
    {
        return $this->repository->index($request);
    }
    public function store(AttachmentLessonRequest $request)
    {
        return $this->repository->store($request);
    }
    public function update($local, AttachmentLessonRequest $request, LessonsAttachment $model)
    {

        return $this->repository->update($local, $request, $model);
    }
    public function delete($local, LessonsAttachment $model)
    {

        return $this->repository->delete($local, $model);
    }
    public function show($local, LessonsAttachment $model)
    {
        return $this->repository->show($local, $model);
    }
    public function multi_actions($local, Request $request)
    {
        return $this->repository->multi_actions($local, $request);
    }



}
