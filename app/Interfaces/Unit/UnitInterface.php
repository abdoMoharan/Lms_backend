<?php
namespace App\Interfaces\Unit;


interface UnitInterface
{
    public function index($request);
    public function store($request);
    public function update($local,$request, $model);
    public function delete($local,$model);
    public function show($local,$model);
    public function showDeleted();
    public function restore($local,$id);
    public function forceDelete($local,$id);
    public function multi_actions($local,$request);
}
