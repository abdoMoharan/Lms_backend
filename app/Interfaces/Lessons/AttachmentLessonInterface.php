<?php
namespace App\Interfaces\Lessons;


interface AttachmentLessonInterface
{
    public function index($request);
    public function store($request);
    public function update($local,$request, $model);
    public function delete($local,$model);
    public function show($local,$model);
    public function multi_actions($local,$request);
}
