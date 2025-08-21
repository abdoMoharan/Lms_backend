<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Admin::where('email', 'admin@admin.com')->exists();
        if (! $admin) {
            Admin::create([
                'name'     => 'Administrator',
                'email'    => 'admin@admin.com',
                'password' => 'password',
            ]);
        }
        $teacher = Teacher::where('email', 'teacher@t.com')->exists();
        if (! $teacher) {
            Teacher::create([
                'name'     => 'teacher',
                'email'    => 'teacher@t.com',
                'password' => 'password',
            ]);
        }
        $student = User::where('email', 'student@s.com')->exists();
        if (! $student) {
            User::create([
                'name'     => 'student',
                'email'    => 'student@s.com',
                'password' => 'password',
            ]);
        }
    }
}
