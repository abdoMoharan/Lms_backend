<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\Auth\AuthController;
use App\Http\Controllers\Api\Admin\TestController;

Route::post('admin/login',[AuthController::class, 'login']);


Route::get('test',[TestController::class,'index'])->middleware(['auth:api_admin']);
