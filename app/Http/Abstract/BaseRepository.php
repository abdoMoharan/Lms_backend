<?php
namespace App\Http\Abstract;

abstract class BaseRepository
{
    abstract public function index($request);
    abstract public function store($request);
    abstract public function update($local, $request, $model);
    abstract public function delete($local, $model);
    abstract public function show($local, $model);
    public function showDeleted()
    {
    }
    public function restore($local, $id)
    {
    }
    public function forceDelete($local, $id)
    {
    }
    public function multi_actions($local, $request)
    {

    }
}
