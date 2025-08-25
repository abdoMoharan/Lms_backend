<?php
namespace App\Interfaces\EducationalStage;

interface EducationalStageInterface
{
    public function index($request);
    public function store($request);
    public function update($request, $educationStage);
    public function delete($educationStage);
    public function show($educationStage);
    public function showDeleted();
    public function restore($id);
    public function forceDelete($id);
    public function multi_actions($request);
}
