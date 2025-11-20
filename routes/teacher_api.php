<?php

use App\Http\Controllers\Api\Exam\ExamController;
use App\Http\Controllers\Api\Lessons\AttachmentLessonController;
use App\Http\Controllers\Api\Teacher\Auth\AuthController;
use App\Http\Controllers\Api\Teacher\Group\GroupController;
use App\Http\Controllers\Api\Teacher\Profile\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('teacher')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('verifyOtp', [AuthController::class, 'verifyOtp']);
    Route::post('resendOtp', [AuthController::class, 'resendOtp']);
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
        Route::prefix('exams')->name('exams.')->controller(ExamController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::post('/update/{model}', 'update')->name('update');
            Route::delete('/delete/{model}', 'delete')->name('delete');
            Route::get('/show/{model}', 'show')->name('show');
            Route::get('/deleted', 'showDeleted')->name('deleted');
            Route::post('/multi-actions', 'multi_actions')->name('multi_actions');
            Route::post('/restore/{id}', 'restore')->name('restore');
            Route::get('/force-delete/{id}', 'forceDelete')->name('force-delete');
        });

        Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
            Route::get('/', 'show')->name('show');
            Route::post('/', 'update')->name('update');
            Route::post('/change-password', 'changePassword')->name('changePassword');
        });
    });

});
