<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Student\Auth\AuthController;



Route::post('student/login',[AuthController::class, 'login']);


