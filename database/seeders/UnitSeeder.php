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
        // الحصول على أول مستخدم من قاعدة البيانات
        $user = User::first();
        $courses = Course::all();

        $units = [
            [
                'sort' => 1,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'status' => 1,
                'translations' => [
                    'ar' => [
                        'name' => 'الوحدة الأولى',
                        'slug' => 'الوحدة-الأولى'
                    ],
                    'en' => [
                        'name' => 'First Unit',
                        'slug' => 'first-unit'
                    ],
                ]
            ],
            [
                'sort' => 2,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'status' => 1,
                'translations' => [
                    'ar' => [
                        'name' => 'الوحدة الثانية',
                        'slug' => 'الوحدة-الثانية'
                    ],
                    'en' => [
                        'name' => 'Second Unit',
                        'slug' => 'second-unit'
                    ],
                ]
            ]
        ];

        foreach ($units as $unitData) {
            $course = $courses->random(); // اختيار دورة عشوائية

            $unit = Unit::create([
                'course_id' => $course->id,
                'created_by' => $unitData['created_by'],
                'updated_by' => $unitData['updated_by'],
                'status' => $unitData['status'],
                'sort' => $unitData['sort'],
            ]);

            foreach ($unitData['translations'] as $locale => $translation) {
                $unit->translations()->create([
                    'locale'      => $locale,
                    'name'        => $translation['name'],
                    'slug'        => $translation['slug'],
                ]);
            }
        }
    }
}
