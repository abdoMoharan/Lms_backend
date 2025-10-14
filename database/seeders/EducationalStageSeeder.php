<?php
namespace Database\Seeders;

use App\Models\EducationalStage;
use App\Models\User;
use Illuminate\Database\Seeder;

class EducationalStageSeeder extends Seeder
{
    public function run()
    {
        // الحصول على أول مستخدم من قاعدة البيانات
        $user = User::first(); // أو يمكنك إنشاء مستخدم جديد إذا لم يكن هناك مستخدم في قاعدة البيانات

        // إنشاء بيانات للمرحلة التعليمية
        $educationalStages = [
            [
                'created_by'   => $user->id,
                'updated_by'   => $user->id,
                'status'       => 1,
                'translations' => [
                    'ar' => [
                        'name' => 'المرحلة التعليمية الأولى',
                        'slug' => 'المرحلة-التعليمية-الأولى',
                    ],
                    'en' => [
                        'name' => 'First Educational Stage',
                        'slug' => 'first-educational-stage',
                    ],
                ],
            ],
            [
                'created_by'   => $user->id,
                'updated_by'   => $user->id,
                'status'       => 1,
                'translations' => [
                    'ar' => [
                        'name' => 'المرحلة التعليمية الثانية',
                        'slug' => 'المرحلة-التعليمية-الثانية',
                    ],
                    'en' => [
                        'name' => 'Second Educational Stage',
                        'slug' => 'second-educational-stage',
                    ],
                ],
            ],
        ];

        foreach ($educationalStages as $stage) {
            $educationalStage = EducationalStage::create([
                'created_by' => $stage['created_by'],
                'updated_by' => $stage['updated_by'],
                'status'     => $stage['status'],
            ]);

            // إضافة الترجمات لكل لغة
            foreach ($stage['translations'] as $locale => $translation) {
                $educationalStage->translations()->create([
                    'locale' => $locale,
                    'name'   => $translation['name'],
                    'slug'   => $translation['slug'],
                ]);
            }
        }
    }
}
