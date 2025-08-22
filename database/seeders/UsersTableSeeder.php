<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // التحقق من وجود حساب للمشرف
        $admin = User::where('email', 'admin@admin.com')->exists();
        if (! $admin) {
            $admin = User::create([
                'username'   => 'adminuser', // يجب إضافة اسم المستخدم
                'first_name' => 'Admin',     // الاسم الأول
                'last_name'  => 'User',      // الاسم الأخير
                'email'      => 'admin@admin.com',
                'phone'      => '1234567890', // إضافة رقم الهاتف
                'user_type'  => 'admin',      // نوع المستخدم
                'password'   => 'password',   // تأكد من تشفير كلمة المرور
            ]);
            $admin->syncRoles(['administrator']);
        }
        // التحقق من وجود حساب للمدرس
        $teacher = User::where('email', 'teacher@t.com')->exists();
        if (! $teacher) {
            User::create([
                'username'   => 'teacheruser', // اسم المستخدم
                'first_name' => 'Teacher',     // الاسم الأول
                'last_name'  => 'User',        // الاسم الأخير
                'email'      => 'teacher@t.com',
                'phone'      => '0987654321', // رقم الهاتف
                'user_type'  => 'teacher',    // نوع المستخدم
                'password'   => 'password',
            ]);
        }

        // التحقق من وجود حساب للطالب
        $student = User::where('email', 'student@s.com')->exists();
        if (! $student) {
            User::create([
                'username'   => 'studentuser', // اسم المستخدم
                'first_name' => 'Student',     // الاسم الأول
                'last_name'  => 'User',        // الاسم الأخير
                'email'      => 'student@s.com',
                'phone'      => '1122334455', // رقم الهاتف
                'user_type'  => 'student',    // نوع المستخدم
                'password'   => 'password',
            ]);
        }
    }
}
