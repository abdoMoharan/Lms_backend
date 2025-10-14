<?php
namespace Database\Seeders;

use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        // الحصول على أول مستخدم من قاعدة البيانات
        $user     = User::first();
        $subjects = Subject::all();

        $courses = [
            [
                'created_by'   => $user->id,
                'updated_by'   => $user->id,
                'status'       => 1,
                'translations' => [
                    'ar' => [
                        'name'        => 'دورة الرياضيات الأساسية',
                        'description' => 'دورة شاملة حول أساسيات الرياضيات.',
                        'slug'        => 'دورة-الرياضيات-الأساسية',
                    ],
                    'en' => [
                        'name'        => 'Basic Mathematics Course',
                        'description' => 'Comprehensive course on basic mathematics.',
                        'slug'        => 'basic-mathematics-course',
                    ],
                ],
            ],
            [
                'created_by'   => $user->id,
                'updated_by'   => $user->id,
                'status'       => 1,
                'translations' => [
                    'ar' => [
                        'name'        => 'دورة اللغة العربية',
                        'description' => 'دورة لتعلم قواعد اللغة العربية.',
                        'slug'        => 'دورة-اللغة-العربية',
                    ],
                    'en' => [
                        'name'        => 'Arabic Language Course',
                        'description' => 'Course to learn Arabic language rules.',
                        'slug'        => 'arabic-language-course',
                    ],
                ],
            ],
        ];

        foreach ($courses as $courseData) {
            $subject = $subjects->random(); // اختيار مادة عشوائية

            $course = Course::create([
                'subject_id' => $subject->id,
                'created_by' => $courseData['created_by'],
                'updated_by' => $courseData['updated_by'],
                'status'     => $courseData['status'],
            ]);

            foreach ($courseData['translations'] as $locale => $translation) {
                $course->translations()->create([
                    'locale'      => $locale,
                    'name'        => $translation['name'],
                    'description' => $translation['description'],
                    'slug'        => $translation['slug'],
                ]);
            }
        }
    }
}
