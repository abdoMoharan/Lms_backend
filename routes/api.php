<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Exam\ExamController;
use App\Http\Controllers\Api\Role\RoleController;
use App\Http\Controllers\Api\Unit\UnitController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\Grade\GradeController;
use App\Http\Controllers\Api\Group\GroupController;
use App\Http\Controllers\Api\Answer\AnswerController;
use App\Http\Controllers\Api\Course\CourseController;
use App\Http\Controllers\Api\Auth\SocialAuthController;
use App\Http\Controllers\Api\Lessons\LessonsController;
use App\Http\Controllers\Api\Subject\SubjectController;
use App\Http\Controllers\Api\Question\QuestionController;
use App\Http\Controllers\Api\Semester\SemesterController;
use App\Http\Controllers\Api\Profile\ProfileUserController;
use App\Http\Controllers\Api\Permission\PermissionController;
use App\Http\Controllers\Api\Lessons\AttachmentLessonController;
use App\Http\Controllers\Api\QuestionType\QuestionTypeController;
use App\Http\Controllers\Api\EducationalStage\EducationalStageController;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
});
Route::post('login-social',[SocialAuthController::class,'login']);
Route::get('get-auth-permissions', [PermissionController::class, 'getAuthPermissions'])->name('get-permissions-auth')->middleware('auth:sanctum');
Route::prefix('{locale}')->middleware('setLocale')->group(function () {
    Route::prefix('admin')->name('admin.')->middleware(['auth:sanctum'])->group(function () {
        Route::prefix('roles')->name('roles.')->controller(RoleController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::get('/{role}', 'show')->name('show');
            Route::put('/update/{role}', 'update')->name('update');
            Route::delete('/delete/{role}', 'delete')->name('delete');
        });

        Route::prefix('permissions')->name('permissions.')->controller(PermissionController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{permission}', 'show')->name('show');
            Route::put('update/{permission}', 'update')->name('update');
        });
        Route::prefix('profile')->name('profile.')->controller(ProfileUserController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'update')->name('update');
            Route::post('/change-password', 'changePassword')->name('changePassword');
        });
        Route::prefix('users')->name('users.')->controller(UserController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/update/{user}', 'update')->name('update');
            Route::delete('/delete/{user}', 'delete')->name('delete');
            Route::get('/show/{user}', 'show')->name('show');
            Route::get('/deleted', 'showDeleted')->name('deleted');
            Route::post('/multi-actions', 'multi_actions')->name('multi_actions');
            Route::post('/restore/{id}', 'restore')->name('restore');
            Route::get('/force-delete/{id}', 'forceDelete')->name('force-delete');
        });
        Route::prefix('education-stages')->name('education-stages.')->controller(EducationalStageController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/update/{model}', 'update')->name('update');
            Route::delete('/delete/{model}', 'delete')->name('delete');
            Route::get('/show/{model}', 'show')->name('show');
            Route::get('/deleted', 'showDeleted')->name('deleted');
            Route::post('/multi-actions', 'multi_actions')->name('multi_actions');
            Route::post('/restore/{id}', 'restore')->name('restore');
            Route::get('/force-delete/{id}', 'forceDelete')->name('force-delete');
        });
        Route::prefix('semesters')->name('semesters.')->controller(SemesterController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/update/{model}', 'update')->name('update');
            Route::delete('/delete/{model}', 'delete')->name('delete');
            Route::get('/show/{model}', 'show')->name('show');
            Route::get('/deleted', 'showDeleted')->name('deleted');
            Route::post('/multi-actions', 'multi_actions')->name('multi_actions');
            Route::post('/restore/{id}', 'restore')->name('restore');
            Route::get('/force-delete/{id}', 'forceDelete')->name('force-delete');
        });
        Route::prefix('grades')->name('grades.')->controller(GradeController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/update/{model}', 'update')->name('update');
            Route::delete('/delete/{model}', 'delete')->name('delete');
            Route::get('/show/{model}', 'show')->name('show');
            Route::get('/deleted', 'showDeleted')->name('deleted');
            Route::post('/multi-actions', 'multi_actions')->name('multi_actions');
            Route::post('/restore/{id}', 'restore')->name('restore');
            Route::get('/force-delete/{id}', 'forceDelete')->name('force-delete');
        });
        Route::prefix('subjects')->name('subjects.')->controller(SubjectController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/update/{model}', 'update')->name('update');
            Route::delete('/delete/{model}', 'delete')->name('delete');
            Route::get('/show/{model}', 'show')->name('show');
            Route::get('/deleted', 'showDeleted')->name('deleted');
            Route::post('/multi-actions', 'multi_actions')->name('multi_actions');
            Route::post('/restore/{id}', 'restore')->name('restore');
            Route::get('/force-delete/{id}', 'forceDelete')->name('force-delete');
        });
        Route::prefix('courses')->name('courses.')->controller(CourseController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/update/{model}', 'update')->name('update');
            Route::delete('/delete/{model}', 'delete')->name('delete');
            Route::get('/show/{model}', 'show')->name('show');
            Route::get('/deleted', 'showDeleted')->name('deleted');
            Route::post('/multi-actions', 'multi_actions')->name('multi_actions');
            Route::post('/restore/{id}', 'restore')->name('restore');
            Route::get('/force-delete/{id}', 'forceDelete')->name('force-delete');
        });
        Route::prefix('units')->name('units.')->controller(UnitController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/update/{model}', 'update')->name('update');
            Route::delete('/delete/{model}', 'delete')->name('delete');
            Route::get('/show/{model}', 'show')->name('show');
            Route::get('/deleted', 'showDeleted')->name('deleted');
            Route::post('/multi-actions', 'multi_actions')->name('multi_actions');
            Route::post('/restore/{id}', 'restore')->name('restore');
            Route::get('/force-delete/{id}', 'forceDelete')->name('force-delete');
        });
        Route::prefix('lessons')->name('lessons.')->controller(LessonsController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::post('/update/{model}', 'update')->name('update');
            Route::delete('/delete/{model}', 'delete')->name('delete');
            Route::get('/show/{model}', 'show')->name('show');
            Route::get('/deleted', 'showDeleted')->name('deleted');
            Route::post('/multi-actions', 'multi_actions')->name('multi_actions');
            Route::post('/restore/{id}', 'restore')->name('restore');
            Route::get('/force-delete/{id}', 'forceDelete')->name('force-delete');
            Route::post('/update-attachment/{model}', 'updateAttachment')->name('update-attachment');
            Route::delete('/delete-attachment/{model}', 'deleteAttachment')->name('delete-attachment');
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
        Route::prefix('question-types')->name('question-types.')->controller(QuestionTypeController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/update/{model}', 'update')->name('update');
            Route::delete('/delete/{model}', 'delete')->name('delete');
            Route::get('/show/{model}', 'show')->name('show');
            Route::get('/deleted', 'showDeleted')->name('deleted');
            Route::post('/multi-actions', 'multi_actions')->name('multi_actions');
            Route::post('/restore/{id}', 'restore')->name('restore');
            Route::get('/force-delete/{id}', 'forceDelete')->name('force-delete');
        });
        Route::prefix('questions')->name('questions.')->controller(QuestionController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/update/{model}', 'update')->name('update');
            Route::delete('/delete/{model}', 'delete')->name('delete');
            Route::get('/show/{model}', 'show')->name('show');
        });
        Route::prefix('answers')->name('answers.')->controller(AnswerController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/update/{model}', 'update')->name('update');
            Route::delete('/delete/{model}', 'delete')->name('delete');
            Route::get('/show/{model}', 'show')->name('show');
        });
        Route::prefix('groups')->name('groups.')->controller(GroupController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/update/{model}', 'update')->name('update');
            Route::delete('/delete/{model}', 'delete')->name('delete');
            Route::get('/show/{model}', 'show')->name('show');
        });
    });
});
//include all routes website front
require base_path('routes/api_web.php');

