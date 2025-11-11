<?php

use App\Http\Controllers\Api\Lessons\AttachmentLessonController;
use App\Http\Controllers\Api\Teacher\Auth\AuthController;
use App\Http\Controllers\Api\Teacher\Group\GroupController;
use Illuminate\Support\Facades\Route;

Route::prefix('teacher')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::middleware(['auth:sanctum', 'activeTeacher'])->group(function () {
        Route::prefix('groups')->name('groups.')->controller(GroupController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::get('/create_meeting', 'create_meeting')->name('create_meeting');
            Route::get('/show/{model}', 'show')->name('show');
            Route::delete('/delete/{model}', 'delete')->name('delete');
        });
        Route::prefix('attachment-lessons')->name('attachment-lessons.')->controller(AttachmentLessonController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::post('/update/{model}', 'update')->name('update');
            Route::delete('/delete/{model}', 'delete')->name('delete');
            Route::get('/show/{model}', 'show')->name('show');
            Route::get('/deleted', 'showDeleted')->name('deleted');
            Route::post('/multi-actions', 'multi_actions')->name('multi_actions');
        });
    });

});
