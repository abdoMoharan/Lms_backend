<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lesson;
use App\Models\Lessons;
use App\Models\Unit;
use App\Models\User;

class LessonSeeder extends Seeder
{
    public function run()
    {
        // الحصول على أول مستخدم من قاعدة البيانات
        $user = User::first();
        $units = Unit::all();

        $lessons = [
            [
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'status' => 1,
                'translations' => [
                    'ar' => [
                        'name' => 'الدرس الأول',
                        'description' => 'مقدمة في الرياضيات.',
                        'content' => 'هنا شرح كامل للدرس الأول.',
                        'slug' => 'الدرس-الأول'
                    ],
                    'en' => [
                        'name' => 'First Lesson',
                        'description' => 'Introduction to Mathematics.',
                        'content' => 'Complete explanation of the first lesson.',
                        'slug' => 'first-lesson'
                    ],
                ]
            ],
            [
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'status' => 1,
                'translations' => [
                    'ar' => [
                        'name' => 'الدرس الثاني',
                        'description' => 'مقدمة متقدمة في الرياضيات.',
                        'content' => 'هنا شرح كامل للدرس الثاني.',
                        'slug' => 'الدرس-الثاني'
                    ],
                    'en' => [
                        'name' => 'Second Lesson',
                        'description' => 'Advanced Mathematics.',
                        'content' => 'Complete explanation of the second lesson.',
                        'slug' => 'second-lesson'
                    ],
                ]
            ]
        ];

        foreach ($lessons as $lessonData) {
            $unit = $units->random(); // اختيار وحدة عشوائية

            $lesson = Lessons::create([
                'unit_id' => $unit->id,
                'created_by' => $lessonData['created_by'],
                'updated_by' => $lessonData['updated_by'],
                'status' => $lessonData['status'],
            ]);

            foreach ($lessonData['translations'] as $locale => $translation) {
                $lesson->translations()->create([
                    'locale' => $locale,
                    'name'   => $translation['name'],
                    'description' => $translation['description'],
                    'content' => $translation['content'],
                    'slug'   => $translation['slug'],
                ]);
            }
        }
    }
}
