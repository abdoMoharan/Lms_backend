<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lessons;
use App\Models\Unit;
use App\Models\User;

class LessonSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();
        $units = Unit::all();

        $lessons = [
            // الدروس الأصلية
            [
                'ar' => ['name' => 'الدرس الأول', 'description' => 'مقدمة في الرياضيات.', 'content' => 'شرح مفصل للأساسيات الرياضية.', 'slug' => 'الدرس-الأول'],
                'en' => ['name' => 'First Lesson', 'description' => 'Introduction to Mathematics.', 'content' => 'Detailed explanation of basic math concepts.', 'slug' => 'first-lesson'],
            ],
            [
                'ar' => ['name' => 'الدرس الثاني', 'description' => 'مقدمة متقدمة في الرياضيات.', 'content' => 'شرح المعادلات والخطوط البيانية.', 'slug' => 'الدرس-الثاني'],
                'en' => ['name' => 'Second Lesson', 'description' => 'Advanced Introduction to Mathematics.', 'content' => 'Explaining equations and graphs.', 'slug' => 'second-lesson'],
            ],
            [
                'ar' => ['name' => 'الدرس الثالث', 'description' => 'المجموعات والعلاقات.', 'content' => 'مفهوم المجموعات والعلاقات الرياضية.', 'slug' => 'الدرس-الثالث'],
                'en' => ['name' => 'Third Lesson', 'description' => 'Sets and Relations.', 'content' => 'Understanding sets and mathematical relations.', 'slug' => 'third-lesson'],
            ],
            [
                'ar' => ['name' => 'الدرس الرابع', 'description' => 'الإحصاء الأساسي.', 'content' => 'مقدمة في مفاهيم الإحصاء.', 'slug' => 'الدرس-الرابع'],
                'en' => ['name' => 'Fourth Lesson', 'description' => 'Basic Statistics.', 'content' => 'Introduction to statistical concepts.', 'slug' => 'fourth-lesson'],
            ],

            // الدروس الإضافية (جديدة ومختلفة)
            [
                'ar' => ['name' => 'الدرس الخامس', 'description' => 'الهندسة المستوية.', 'content' => 'مقدمة في الأشكال الهندسية والزوايا.', 'slug' => 'الدرس-الخامس'],
                'en' => ['name' => 'Fifth Lesson', 'description' => 'Plane Geometry.', 'content' => 'Introduction to geometric shapes and angles.', 'slug' => 'fifth-lesson'],
            ],
            [
                'ar' => ['name' => 'الدرس السادس', 'description' => 'حساب التفاضل والتكامل.', 'content' => 'أساسيات المشتقات والتكامل.', 'slug' => 'الدرس-السادس'],
                'en' => ['name' => 'Sixth Lesson', 'description' => 'Calculus Basics.', 'content' => 'Fundamentals of derivatives and integration.', 'slug' => 'sixth-lesson'],
            ],
            [
                'ar' => ['name' => 'الدرس السابع', 'description' => 'الجبر الخطي.', 'content' => 'المصفوفات والمتجهات.', 'slug' => 'الدرس-السابع'],
                'en' => ['name' => 'Seventh Lesson', 'description' => 'Linear Algebra.', 'content' => 'Matrices and vectors explained.', 'slug' => 'seventh-lesson'],
            ],
            [
                'ar' => ['name' => 'الدرس الثامن', 'description' => 'الاحتمالات.', 'content' => 'المفاهيم الأساسية لنظرية الاحتمالات.', 'slug' => 'الدرس-الثامن'],
                'en' => ['name' => 'Eighth Lesson', 'description' => 'Probability Theory.', 'content' => 'Basic principles of probability.', 'slug' => 'eighth-lesson'],
            ],
        ];

        foreach ($lessons as $translationSet) {
            $unit = $units->random();

            $lesson = Lessons::create([
                'unit_id' => $unit->id,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'status' => 1,
            ]);

            foreach (['ar', 'en'] as $locale) {
                $lesson->translations()->create([
                    'locale' => $locale,
                    'name' => $translationSet[$locale]['name'],
                    'description' => $translationSet[$locale]['description'],
                    'content' => $translationSet[$locale]['content'],
                    'slug' => $translationSet[$locale]['slug'],
                ]);
            }
        }
    }
}
