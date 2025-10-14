<?php
namespace Database\Seeders;

use Database\Seeders\EducationalStageSeeder;
use Database\Seeders\GradeSeeder;
use Database\Seeders\LessonSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\SubjectSeeder;
use Database\Seeders\UnitSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(EducationalStageSeeder::class);
        $this->call(SemesterSeeder::class);
        $this->call(GradeSeeder::class);
        $this->call(SubjectSeeder::class);
        $this->call(CourseSeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(LessonSeeder::class);
        // $this->call(QuestionTypeSeeder::class);
        // $this->call(GroupSeeder::class);
    }
}
