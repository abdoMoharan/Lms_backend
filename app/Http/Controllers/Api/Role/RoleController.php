<?php
namespace App\Http\Controllers\Api\Role;


use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Role\RoleRequest;
use App\Http\Resources\Roles\RolesResource;
use App\Models\CustomPermission;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function __construct()
    {
        $model = CustomPermission::query()->get();
        syncPermisions($model);
    }
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10); // Default per page is 10
            $roles   = Role::query()->paginate($perPage);

            if ($roles->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No roles found', []);
            }

            // إزالة الأذونات من استجابة الأدوار
            $rolesData = $roles->map(function ($role) {
                $roleData = [
                    'id'         => $role->id,
                    'name'       => $role->name,
                    'guard_name' => $role->guard_name,
                    'created_at' => $role->created_at,
                    'updated_at' => $role->updated_at,
                ];
                $permissions = [];
                foreach ($role->permissions as $perm) {
                    $nameParts = explode('.', $perm->name);

                    // إذا كان الاسم يحتوي على 'api.' في بدايته
                    if (strpos($perm->name, 'admin.') === 0) {
                        // إذا كان يحتوي على 'api.'، نأخذ الجزء الذي بعد 'api.'
                        $group = $nameParts[1] ?? 'general';
                    } else {
                        // إذا لم يحتوي على 'api.'، نأخذ أول جزء كـ group
                        $group = $nameParts[0] ?? 'general';
                    }

                                                        // ناخد آخر جزء بعد النقطة كـ name فقط
                    $simpleName      = end($nameParts); // يأخد آخر جزء بعد النقطة
                    $namePartTrans   = explode('.', $perm->trans_name);
                    $simpleNameTrans = end($namePartTrans); // يأخد آخر جزء بعد النقطة

                    $permissions[$group][] = [
                        'id'         => $perm->id,
                        'name'       => $simpleName,
                        'trans_name' => $simpleNameTrans,
                    ];
                }
                $roleData['permissions'] = $permissions;
                return $roleData;
            });

            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Roles fetched successfully', ['roles' => $rolesData]);
        } catch (Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, 'An error occurred', ['error' => $e->getMessage()]);
        }
    }
    public function store(RoleRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->getData();
            $role = Role::create($data);
            $role->syncPermissions($data['permissions']);
            DB::commit();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Role created successfully and assign Permissions to this role', new RolesResource($role));
        } catch (Exception $e) {
            DB::rollBack();
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, $e->getMessage());
        }
    }

    public function update($local,Request $request, Role $role)
    {
        try {
            DB::beginTransaction();

            // التحقق من البيانات المرسلة
            $data = $request->validate([
                'name'          => 'required|string|max:255',
                'permissions'   => 'required|array',
                'permissions.*' => 'exists:permissions,id',
            ]);

            // العثور على الدور حسب المعرف
            // $role = Role::findOrFail($data['role_id']);

            // إذا تم تغيير الاسم، تحقق من وجود دور بنفس الاسم وguard_name
            if ($role->name !== $data['name']) {
                // إذا تغير الاسم، تحقق من وجود دور بنفس الاسم وguard_name
                $existingRole = Role::where('name', $data['name'])
                    ->where('guard_name', 'web')
                    ->first();

                if ($existingRole) {
                    throw new Exception('Role with the same name and guard_name already exists.');
                }

                // تحديث اسم الدور إذا تم تغييره
                $role->name = $data['name'];
            }

            // تحديث guard_name إذا كان متغيرًا أو تم تغييره
            $role->guard_name = 'web';
            $role->save();

            // جلب الصلاحيات المرسلة
            $permissions = CustomPermission::whereIn('id', $request->permissions)
                ->where('guard_name', 'web')
                ->get();

            // إذا كانت الصلاحيات قد تغيرت أو تم إرسال صلاحيات جديدة، نقوم بتحديثها
            if ($permissions->isNotEmpty()) {
                // مزامنة الصلاحيات مع الدور
                $role->syncPermissions($permissions);
            }

            DB::commit();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Role updated successfully and permissions updated', new RolesResource($role));
        } catch (Exception $e) {
            DB::rollBack();
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, $e->getMessage());
        }
    }

    public function show($local,Role $role)
    {
        $rolesData              = $role;
        $rolesData->permissions = $role->permissions;
        $permissions            = [];
        foreach ($role->permissions as $perm) {
            $nameParts = explode('.', $perm->name);

            // إذا كان الاسم يحتوي على 'api.' في بدايته
            if (strpos($perm->name, 'admin.') === 0) {
                // إذا كان يحتوي على 'api.'، نأخذ الجزء الذي بعد 'api.'
                $group = $nameParts[1] ?? 'general';
            } else {
                // إذا لم يحتوي على 'api.'، نأخذ أول جزء كـ group
                $group = $nameParts[0] ?? 'general';
            }

                                                // ناخد آخر جزء بعد النقطة كـ name فقط
            $simpleName      = end($nameParts); // يأخد آخر جزء بعد النقطة
            $namePartTrans   = explode('.', $perm->trans_name);
            $simpleNameTrans = end($namePartTrans); // يأخد آخر جزء بعد النقطة

            // استخدام القسم كـ key ديناميكي

            $permissions[$group][] = [
                'id'         => $perm->id,
                'name'       => $simpleName,
                'trans_name' => $simpleNameTrans,
            ];
        }
        $roleData = [
            'id'          => $role->id,
            'name'        => $role->name,
            'guard_name'  => $role->guard_name,
            'created_at'  => $role->created_at,
            'permissions' => $permissions,
        ];
        return $roleData;

        return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Roles fetched successfully', ['roles' => $rolesData]);
    }

    public function delete($local,Role $role)
    {
        $role->delete();
        return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Role deleted successfully');
    }
}
