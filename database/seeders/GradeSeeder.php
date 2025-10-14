<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grade;
use App\Models\EducationalStage;
use App\Models\User;

class GradeSeeder extends Seeder
{
    public function run()
    {
        // الحصول على أول مستخدم من قاعدة البيانات
        $user = User::first();
        $educationalStages = EducationalStage::all(); // الحصول على كل المراحل التعليمية

        $grades = [
            [
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'status' => 1,
                'translations' => [
                    'ar' => [
                        'name' => 'الصف الأول',
                        'slug' => 'الصف-الأول'
                    ],
                    'en' => [
                        'name' => 'First Grade',
                        'slug' => 'first-grade'
                    ]
                ]
            ],
            [
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'status' => 1,
                'translations' => [
                    'ar' => [
                        'name' => 'الصف الثاني',
                        'slug' => 'الصف-الثاني'
                    ],
                    'en' => [
                        'name' => 'Second Grade',
                        'slug' => 'second-grade'
                    ]
                ]
            ]
        ];

        foreach ($grades as $gradeData) {
            $educationalStage = $educationalStages->random(); // اختيار مرحلة تعليمية عشوائية

            $grade = Grade::create([
                'stage_id' => $educationalStage->id,
                'created_by' => $gradeData['created_by'],
                'updated_by' => $gradeData['updated_by'],
                'status' => $gradeData['status'],
            ]);

            foreach ($gradeData['translations'] as $locale => $translation) {
                $grade->translations()->create([
                    'locale' => $locale,
                    'name'   => $translation['name'],
                    'slug'   => $translation['slug'],
                ]);
            }
        }
    }
}
