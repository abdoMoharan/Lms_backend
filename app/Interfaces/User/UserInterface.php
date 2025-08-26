<?php
namespace App\Interfaces\User;

interface UserInterface
{

    public function index($request);
    public function store($request);
    public function update($local,$request, $user);
    public function delete($local,$user);
    public function show($local,$user);
    public function showDeleted();
    public function restore($local,$id);
    public function forceDelete($local,$id);
    public function multi_actions($local,$request);
}
