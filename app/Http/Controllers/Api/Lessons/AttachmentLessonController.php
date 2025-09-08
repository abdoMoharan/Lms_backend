<?php

namespace App\Http\Controllers\Api\Lessons;

use Illuminate\Http\Request;
use App\Models\LessonsAttachment;
use App\Http\Controllers\Controller;
use App\Interfaces\Lessons\AttachmentLessonInterface;
use App\Http\Requests\Api\Lessons\AttachmentLessonRequest;

class AttachmentLessonController extends Controller
{
    public AttachmentLessonInterface $interface;
    public function __construct(AttachmentLessonInterface $interface)
    {
        $this->interface = $interface;
    }
    public function index(Request $request)
    {
        return $this->interface->index($request);
    }
    public function store(AttachmentLessonRequest $request)
    {
        return $this->interface->store($request);
    }
    public function update($local, AttachmentLessonRequest $request, LessonsAttachment $model)
    {

        return $this->interface->update($local, $request, $model);
    }
    public function delete($local, LessonsAttachment $model)
    {

        return $this->interface->delete($local, $model);
    }
    public function show($local, LessonsAttachment $model)
    {
        return $this->interface->show($local, $model);
    }
    public function multi_actions($local, Request $request)
    {
        return $this->interface->multi_actions($local, $request);
    }



}
