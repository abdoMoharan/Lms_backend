<?php
namespace App\Providers;

use App\Interfaces\User\UserInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\User\UserRepository;
use App\Interfaces\Semester\SemesterInterface;
use App\Repositories\Semester\SemesterRepository;
use App\Interfaces\EducationalStage\EducationalStageInterface;
use App\Repositories\EducationalStage\EducationalStageRepository;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->singleton(UserInterface::class, UserRepository::class);
        $this->app->singleton(EducationalStageInterface::class, EducationalStageRepository::class);
        $this->app->singleton(SemesterInterface::class, SemesterRepository::class);
    }
}
