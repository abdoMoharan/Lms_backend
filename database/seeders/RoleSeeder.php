<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\CustomPermission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // جلب جميع الصلاحيات الحالية وإنشاء أي صلاحيات مفقودة`
        $permissions = CustomPermission::query()->get();
     syncPermisions($permissions); // تزامن الصلاحيات مع النظام

        // إنشاء دور "administrator" مع guard_name = 'admin' إذا لم يكن موجودًا
        $role = Role::firstOrCreate(
            ['name' => 'administrator', 'guard_name' => 'web'],
            ['name' => 'administrator', 'guard_name' => 'web']
        );
        $role->syncPermissions(CustomPermission::where('guard_name', 'web')->get()->pluck('id')->toArray());
    }
}
