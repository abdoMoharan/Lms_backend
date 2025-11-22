<?php

use App\Http\Middleware\SetLocale;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: [
            __DIR__ . '/../routes/api.php',
            __DIR__ . '/../routes/teacher_api.php', // 👈 أضف هذا السطر
            __DIR__ . '/../routes/api_web.php', // 👈 أضف هذا السطر
        ],
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->appendToGroup('setLocale', [
            SetLocale::class,
        ]);
        $middleware->appendToGroup('activeTeacher', [
            \App\Http\Middleware\ActiveTeacher::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function (Schedule $schedule) { // Define scheduled commands here
        $schedule->command('zoom:create-meetings')->dailyAt('08:00');
    })->create();
