<?php

use App\Http\Controllers\Api\Teacher\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('teacher')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});
