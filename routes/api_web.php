<?php

use App\Http\Controllers\Api\Teacher\Exam\ExamAttemptController;
use App\Http\Controllers\Api\WebSite\Course\CourseController;
use App\Http\Controllers\Api\WebSite\EducationalStage\EducationalStageController;
use App\Http\Controllers\Api\WebSite\Grade\GradeController;
use App\Http\Controllers\Api\WebSite\Semester\SemesterController;
use App\Http\Controllers\Api\WebSite\Subject\SubjectController;
use App\Http\Controllers\Api\WebSite\Teacher\TeacherController;
use App\Http\Controllers\Api\Week\WeekController;
use Illuminate\Support\Facades\Route;

Route::prefix('{locale}')->middleware(['setLocale'])->group(function () {
    Route::prefix('website')->name('website.')->group(function () {
        Route::prefix('education-stages')->name('education-stages.')->controller(EducationalStageController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/show/{id}/{slug?}', 'show')->name('show');
        });
        Route::prefix('semesters')->name('semesters.')->controller(SemesterController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/show/{id}/{slug?}', 'show')->name('show');
        });
        Route::prefix('grades')->name('grades.')->controller(GradeController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/show/{id}/{slug?}', 'show')->name('show');
        });
        Route::prefix('subjects')->name('subjects.')->controller(SubjectController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/show/{id}/{slug?}', 'show')->name('show');
        });
        Route::prefix('courses')->name('courses.')->controller(CourseController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}/{slug?}', 'show')->name('show');
        });
        Route::prefix('teachers')->name('teachers.')->controller(TeacherController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'show')->name('show');
        });

        Route::prefix('weeks')->name('weeks.')->controller(WeekController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/show/{model}', 'show')->name('show');
        });
        Route::post('exam-attempts/start/{examId}', [ExamAttemptController::class, 'start'])->middleware('auth:sanctum');
        Route::post('exam-attempts/submit/{examId}', [ExamAttemptController::class, 'submit'])->middleware('auth:sanctum');
    });
});
