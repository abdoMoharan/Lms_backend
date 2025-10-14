<?php
namespace Database\Seeders;

use App\Models\Semester;
use App\Models\User;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    public function run()
    {
        // الحصول على أول مستخدم من قاعدة البيانات
        $user = User::first();

        // إنشاء بيانات للفصول الدراسية
        $semesters = [
            [
                'created_by'   => $user->id,
                'updated_by'   => $user->id,
                'status'       => 1,
                'translations' => [
                    'ar' => [
                        'name' => 'الفصل الدراسي الأول',
                        'slug' => 'الفصل-الدراسي-الأول',
                    ],
                    'en' => [
                        'name' => 'First Semester',
                        'slug' => 'first-semester',
                    ],
                ],
            ],
            [
                'created_by'   => $user->id,
                'updated_by'   => $user->id,
                'status'       => 1,
                'translations' => [
                    'ar' => [
                        'name' => 'الفصل الدراسي الثاني',
                        'slug' => 'الفصل-الدراسي-الثاني',
                    ],
                    'en' => [
                        'name' => 'Second Semester',
                        'slug' => 'second-semester',
                    ],
                ],
            ],
        ];

        foreach ($semesters as $semester) {
            $semesterModel = Semester::create([
                'created_by' => $semester['created_by'],
                'updated_by' => $semester['updated_by'],
                'status'     => $semester['status'],
            ]);

            foreach ($semester['translations'] as $locale => $translation) {
                $semesterModel->translations()->create([
                    'locale' => $locale,
                    'name'   => $translation['name'],
                    'slug'   => $translation['slug'],
                ]);
            }
        }
    }
}
