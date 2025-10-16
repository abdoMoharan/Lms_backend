<?php
namespace App\Providers;

use App\Http\Abstract\BaseRepository;
use App\Interfaces\ProfileInterface;
use App\Repositories\Answer\AnswerRepository;
use App\Repositories\Course\CourseRepository;
use App\Repositories\EducationalStage\EducationalStageRepository;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\Grade\GradeRepository;
use App\Repositories\Group\GroupRepository;
use App\Repositories\Lessons\AttachmentLessonRepository;
use App\Repositories\Lessons\LessonRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\QuestionType\QuestionTypeRepository;
use App\Repositories\Question\QuestionRepository;
use App\Repositories\Semester\SemesterRepository;
use App\Repositories\Subject\SubjectRepository;
use App\Repositories\Unit\UnitRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            BaseRepository::class,           // الكلاس المجرد (abstract class)
            EducationalStageRepository::class// الكلاس الذي ينفذ الـ abstract class
        );
        $this->app->bind(
            BaseRepository::class, // الكلاس المجرد (abstract class)
            UserRepository::class  // الكلاس الذي ينفذ الـ abstract class
        );
        $this->app->bind(
            BaseRepository::class,  // الكلاس المجرد (abstract class)
            ProfileRepository::class// الكلاس الذي ينفذ الـ abstract class
        );
        $this->app->bind(
            BaseRepository::class,   // الكلاس المجرد (abstract class)
            SemesterRepository::class// الكلاس الذي ينفذ الـ abstract class
        );
        $this->app->bind(
            BaseRepository::class, // الكلاس المجرد (abstract class)
            GradeRepository::class // الكلاس الذي ينفذ الـ abstract class
        );
        $this->app->bind(
            BaseRepository::class,  // الكلاس المجرد (abstract class)
            SubjectRepository::class// الكلاس الذي ينفذ الـ abstract class
        );
        $this->app->bind(
            BaseRepository::class, // الكلاس المجرد (abstract class)
            CourseRepository::class// الكلاس الذي ينفذ الـ abstract class
        );
        $this->app->bind(
            BaseRepository::class, // الكلاس المجرد (abstract class)
            UnitRepository::class  // الكلاس الذي ينفذ الـ abstract class
        );
        $this->app->bind(
            BaseRepository::class, // الكلاس المجرد (abstract class)
            LessonRepository::class// الكلاس الذي ينفذ الـ abstract class
        );
        $this->app->bind(
            BaseRepository::class,           // الكلاس المجرد (abstract class)
            AttachmentLessonRepository::class// الكلاس الذي ينفذ الـ abstract class
        );
        $this->app->bind(
            BaseRepository::class, // الكلاس المجرد (abstract class)
            ExamRepository::class  // الكلاس الذي ينفذ الـ abstract class
        );
        $this->app->bind(
            BaseRepository::class,       // الكلاس المجرد (abstract class)
            QuestionTypeRepository::class// الكلاس الذي ينفذ الـ abstract class
        );
        $this->app->bind(
            BaseRepository::class,   // الكلاس المجرد (abstract class)
            QuestionRepository::class// الكلاس الذي ينفذ الـ abstract class
        );
        $this->app->bind(
            BaseRepository::class, // الكلاس المجرد (abstract class)
            AnswerRepository::class// الكلاس الذي ينفذ الـ abstract class
        );
        $this->app->bind(
            BaseRepository::class, // الكلاس المجرد (abstract class)
            GroupRepository::class // الكلاس الذي ينفذ الـ abstract class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->singleton(ProfileInterface::class, ProfileRepository::class);

    }
}
