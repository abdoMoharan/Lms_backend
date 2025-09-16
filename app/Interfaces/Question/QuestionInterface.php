<?php
namespace App\Interfaces\Question;


interface QuestionInterface
{
    public function index($request);
    public function store($request);
    public function update($local,$request, $model);
    public function delete($local,$model);
    public function show($local,$model);
}
