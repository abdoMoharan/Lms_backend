<?php
namespace Database\Seeders;

use App\Models\EducationalStage;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        // الحصول على أول مستخدم من قاعدة البيانات
        $user              = User::first();


        $subjects = [
            [

                'created_by'   => $user->id,
                'updated_by'   => $user->id,
                'status'       => 1,
                'translations' => [
                    'ar' => [
                        'name' => 'مادة الرياضيات',
                        'slug' => 'مادة-الرياضيات',
                    ],
                    'en' => [
                        'name' => 'Mathematics Subject',
                        'slug' => 'mathematics-subject',
                    ],
                ],
            ],
            [
                'created_by'   => $user->id,
                'updated_by'   => $user->id,
                'status'       => 1,
                'translations' => [
                    'ar' => [
                        'name' => 'مادة اللغة العربية',
                        'slug' => 'مادة-اللغة-العربية',
                    ],
                    'en' => [
                        'name' => 'Arabic Language Subject',
                        'slug' => 'arabic-language-subject',
                    ],
                ],
            ],
        ];

        foreach ($subjects as $subjectData) {


            $subject = Subject::create([

                'created_by' => $subjectData['created_by'],
                'updated_by' => $subjectData['updated_by'],
                'status'     => $subjectData['status'],

            ]);
            foreach ($subjectData['translations'] as $locale => $translation) {
                $subject->translations()->create([
                    'locale' => $locale,
                    'name'   => $translation['name'],
                    'slug'   => $translation['slug'],
                ]);
            }
        }
    }
}
