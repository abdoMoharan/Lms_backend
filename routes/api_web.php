<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Week\WeekController;
use App\Http\Controllers\Api\WebSite\Auth\AuthController;
use App\Http\Controllers\Api\WebSite\Grade\GradeController;
use App\Http\Controllers\Api\WebSite\Course\CourseController;
use App\Http\Controllers\Api\WebSite\Payment\PaymentController;
use App\Http\Controllers\Api\WebSite\Subject\SubjectController;
use App\Http\Controllers\Api\WebSite\Teacher\TeacherController;
use App\Http\Controllers\Api\Teacher\Exam\ExamAttemptController;
use App\Http\Controllers\Api\WebSite\Semester\SemesterController;
use App\Http\Controllers\Api\WebSite\GroupDetails\GroupDetailsController;
use App\Http\Controllers\Api\Websit\GroupRegister\GroupRegisterController;
use App\Http\Controllers\Api\WebSite\EducationalStage\EducationalStageController;
use App\Http\Controllers\Api\WebSite\LessonAttachment\LessonAttachmentController;

Route::match(['GET', 'POST'], '/payment/callback', [PaymentController::class, 'callBack']);
Route::prefix('{locale}')->middleware(['setLocale'])->group(function () {
    Route::prefix('website')->name('website.')->group(function () {
//authRoute
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::prefix('group-registers')->name('group-registers.')->controller(GroupRegisterController::class)->group(function () {
                Route::post('/', 'store')->name('store');
                Route::get('/', 'index')->name('index');
                Route::get('/{id}', 'show')->name('show');
            });
            Route::post('exam-attempts/start/{examId}', [ExamAttemptController::class, 'start']);
            Route::post('exam-attempts/submit/{examId}', [ExamAttemptController::class, 'submit']);
        Route::post('payment/process', [PaymentController::class, 'paymentProcess']);
        });
//authRoute
        Route::controller(AuthController::class)->group(function () {
            Route::post('login', 'login');
            Route::post('register', 'register');
            Route::post('logout', 'logout')->middleware('auth:sanctum');
        });
        Route::prefix('education-stages')->name('education-stages.')->controller(EducationalStageController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}/{slug?}', 'show')->name('show');
        });
        Route::prefix('semesters')->name('semesters.')->controller(SemesterController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}/{slug?}', 'show')->name('show');
        });
        Route::prefix('grades')->name('grades.')->controller(GradeController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}/{slug?}', 'show')->name('show');
        });
        Route::prefix('subjects')->name('subjects.')->controller(SubjectController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}/{slug?}', 'show')->name('show');
        });
        Route::prefix('courses')->name('courses.')->controller(CourseController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}/{slug?}', 'show')->name('show');
        });
        Route::prefix('teachers')->name('teachers.')->controller(TeacherController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'show')->name('show');
        });
        // Route::prefix('groups')->name('groups.')->controller(GroupDetailsController::class)->group(function () {
        //     // Route::get('/', 'index')->name('index');
        //     Route::get('/{id}/', 'show')->name('show');
        // });
        Route::prefix('weeks')->name('weeks.')->controller(WeekController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{model}', 'show')->name('show');
        });
        Route::prefix('lesson-attachments')->name('lesson-attachments.')->controller(LessonAttachmentController::class)->group(function () {
            // Route::get('/', 'index')->name('index');
            Route::get('/{model}', 'show')->name('show');
        });
        Route::get('details-group/{id}', [GroupDetailsController::class, 'show'])->name('details-group.show');

    });
});
