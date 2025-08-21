<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\TestController;
use App\Http\Controllers\Api\Teacher\Auth\AuthController;



Route::post('teacher/login',[AuthController::class, 'login']);


Route::get('teacher/test', [TestController::class, 'index'])->middleware('auth:api_teacher');

