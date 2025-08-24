<?php
namespace App\Interfaces;

interface ProfileInterface
{
    public function index();
    public function update($request);
    public function changePassword($request);
}
