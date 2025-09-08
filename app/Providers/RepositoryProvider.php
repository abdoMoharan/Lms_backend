<?php
namespace App\Providers;

use App\Interfaces\Unit\UnitInterface;
use App\Interfaces\User\UserInterface;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\Grade\GradeInterface;
use App\Repositories\Unit\UnitRepository;
use App\Repositories\User\UserRepository;
use App\Interfaces\Course\CourseInterface;
use App\Interfaces\Lessons\LessonInterface;
use App\Repositories\Grade\GradeRepository;
use App\Interfaces\Subject\SubjectInterface;
use App\Repositories\Course\CourseRepository;
use App\Interfaces\Semester\SemesterInterface;
use App\Repositories\Lessons\LessonRepository;
use App\Repositories\Subject\SubjectRepository;
use App\Repositories\Semester\SemesterRepository;
use App\Interfaces\EducationalStage\EducationalStageInterface;
use App\Interfaces\Lessons\AttachmentLessonInterface;
use App\Repositories\EducationalStage\EducationalStageRepository;
use App\Repositories\Lessons\AttachmentLessonRepository;

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
        $this->app->singleton(UnitInterface::class,UnitRepository::class);
        $this->app->singleton(LessonInterface::class,LessonRepository::class);
        $this->app->singleton(AttachmentLessonInterface::class,AttachmentLessonRepository::class);
    }
}
