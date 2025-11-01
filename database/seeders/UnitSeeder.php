<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;
use App\Models\Course;
use App\Models\User;

class UnitSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();
        $courses = Course::all();

        $units = [
            // ---- الوحدات الأصلية ----
            [
                'sort' => 1,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'status' => 1,
                'translations' => [
                    'ar' => ['name' => 'الوحدة الأولى: المفاهيم الأساسية', 'slug' => 'الوحدة-الأولى'],
                    'en' => ['name' => 'First Unit: Basic Concepts', 'slug' => 'first-unit'],
                ],
            ],
            [
                'sort' => 2,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'status' => 1,
                'translations' => [
                    'ar' => ['name' => 'الوحدة الثانية: المفاهيم المتقدمة', 'slug' => 'الوحدة-الثانية'],
                    'en' => ['name' => 'Second Unit: Advanced Concepts', 'slug' => 'second-unit'],
                ],
            ],
            [
                'sort' => 3,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'status' => 1,
                'translations' => [
                    'ar' => ['name' => 'الوحدة الثالثة: التطبيقات العملية', 'slug' => 'الوحدة-الثالثة'],
                    'en' => ['name' => 'Third Unit: Practical Applications', 'slug' => 'third-unit'],
                ],
            ],
            [
                'sort' => 4,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'status' => 1,
                'translations' => [
                    'ar' => ['name' => 'الوحدة الرابعة: التقييم والمراجعة', 'slug' => 'الوحدة-الرابعة'],
                    'en' => ['name' => 'Fourth Unit: Assessment and Review', 'slug' => 'fourth-unit'],
                ],
            ],

            // ---- الوحدات الجديدة (إضافية) ----
            [
                'sort' => 5,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'status' => 1,
                'translations' => [
                    'ar' => ['name' => 'الوحدة الخامسة: التفكير النقدي والتحليل', 'slug' => 'الوحدة-الخامسة'],
                    'en' => ['name' => 'Fifth Unit: Critical Thinking and Analysis', 'slug' => 'fifth-unit'],
                ],
            ],
            [
                'sort' => 6,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'status' => 1,
                'translations' => [
                    'ar' => ['name' => 'الوحدة السادسة: حل المشكلات', 'slug' => 'الوحدة-السادسة'],
                    'en' => ['name' => 'Sixth Unit: Problem Solving', 'slug' => 'sixth-unit'],
                ],
            ],
            [
                'sort' => 7,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'status' => 1,
                'translations' => [
                    'ar' => ['name' => 'الوحدة السابعة: مهارات الاتصال', 'slug' => 'الوحدة-السابعة'],
                    'en' => ['name' => 'Seventh Unit: Communication Skills', 'slug' => 'seventh-unit'],
                ],
            ],
            [
                'sort' => 8,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'status' => 1,
                'translations' => [
                    'ar' => ['name' => 'الوحدة الثامنة: القيادة واتخاذ القرار', 'slug' => 'الوحدة-الثامنة'],
                    'en' => ['name' => 'Eighth Unit: Leadership and Decision Making', 'slug' => 'eighth-unit'],
                ],
            ],
        ];

        foreach ($units as $unitData) {
            $course = $courses->random(); // ربط كل وحدة بدورة عشوائية

            $unit = Unit::create([
                'course_id' => $course->id,
                'created_by' => $unitData['created_by'],
                'updated_by' => $unitData['updated_by'],
                'status' => $unitData['status'],
                'sort' => $unitData['sort'],
            ]);

            foreach ($unitData['translations'] as $locale => $translation) {
                $unit->translations()->create([
                    'locale' => $locale,
                    'name' => $translation['name'],
                    'slug' => $translation['slug'],
                ]);
            }
        }
    }
}
