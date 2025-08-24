<?php
namespace App\Interfaces\User;

interface UserInterface
{

    public function index($request);
    public function store($request);
    public function update($request, $user);
    public function delete($user);
    public function show($user);
    public function showDeleted();
    public function restore($id);
    public function forceDelete($id);
    public function multi_actions($request);
}
