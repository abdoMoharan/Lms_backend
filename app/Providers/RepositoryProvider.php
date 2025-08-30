<?php
namespace App\Providers;

use App\Interfaces\Course\CourseInterface;
use App\Interfaces\User\UserInterface;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\Grade\GradeInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\Grade\GradeRepository;
use App\Interfaces\Subject\SubjectInterface;
use App\Interfaces\Semester\SemesterInterface;
use App\Repositories\Subject\SubjectRepository;
use App\Repositories\Semester\SemesterRepository;
use App\Interfaces\EducationalStage\EducationalStageInterface;
use App\Repositories\Course\CourseRepository;
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
        $this->app->singleton(GradeInterface::class, GradeRepository::class);
        $this->app->singleton(SubjectInterface::class, SubjectRepository::class);
        $this->app->singleton(CourseInterface::class,CourseRepository::class);
    }
}
