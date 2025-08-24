<?php
namespace App\Http\Controllers\Api\Permission;



use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\CustomPermission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct()
    {
        $model = CustomPermission::query()->get();
        syncPermisions($model);
    }

    public function index()
    {
        try {
            $permissions = CustomPermission::all();

            if ($permissions->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No permissions found', []);
            }
            $result = [];

            foreach ($permissions as $permission) {
                $section   = $permission->section_name ?? 'general';
                $nameParts = explode('.', $permission->name);

                // إذا كان الاسم يحتوي على 'api.' في بدايته
                if (strpos($permission->name, 'admin.') === 0) {
                    // إذا كان يحتوي على 'api.'، نأخذ الجزء الذي بعد 'api.'
                    $group = $nameParts[1] ?? 'general';
                } else {
                    // إذا لم يحتوي على 'api.'، نأخذ أول جزء كـ group
                    $group = $nameParts[0] ?? 'general';
                }

                                                    // ناخد آخر جزء بعد النقطة كـ name فقط
                $simpleName      = end($nameParts); // يأخد آخر جزء بعد النقطة
                $namePartTrans   = explode('.', $permission->trans_name);
                $simpleNameTrans = end($namePartTrans); // يأخد آخر جزء بعد النقطة


                if (! isset($result[$group])) {
                    $result[$group] = [];
                }

                $result[$group][] = [
                    'id'         => $permission->id,
                    'name'       => $simpleName,
                    'trans_name' => $simpleNameTrans,
                ];
            }

            return ApiResponse::apiResponse(
                JsonResponse::HTTP_OK,
                'Permissions grouped by section and group',
                $result
            );
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'An error occurred',
                ['error' => $e->getMessage()]
            );
        }
    }

    public function show(CustomPermission $permission)
    {
        try {
            $section = $permission->section_name ?? 'general';
            $group   = explode('.', $permission->name)[0] ?? 'general';

            $nameParts       = explode('.', $permission->name);
            $simpleName      = end($nameParts);
            $namePartTrans   = explode('.', $permission->trans_name);
            $simpleNameTrans = end($namePartTrans); // يأخد آخر جزء بعد النقطة

            $result = [
                $section => [
                    $group => [
                        [
                            'id'         => $permission->id,
                            'name'       => $simpleName,
                            'trans_name' => $simpleNameTrans,
                        ],
                    ],
                ],
            ];

            return ApiResponse::apiResponse(
                JsonResponse::HTTP_OK,
                'Permission details',
                $result
            );
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'An error occurred',
                ['error' => $e->getMessage()]
            );
        }
    }

    public function update(Request $request, CustomPermission $permission)
    {
        $data = $request->validate([
            'trans_name' => 'required|string|max:255',
        ]);
        $permission->update($data);
        return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Permission updated successfully', $permission->trans_name);
    }

public function getAuthPermissions(Request $request)
{
    $user = $request->user();
    $roles = $user->roles()->with('permissions')->get(); // جلب الأدوار مع الأذونات
    $roleData = [];

    try {
        foreach ($roles as $role) {
            $permissions = [];
            foreach ($role->permissions as $perm) {
                $section = $perm->section_name ?: 'general'; // إذا كانت section_name غير موجودة، استخدم 'general'
                $nameParts = explode('.', $perm->name);

                // تحديد المجموعة بناءً على الاسم
                $group = (strpos($perm->name, 'admin.') === 0) ? $nameParts[1] ?? 'general' : $nameParts[0] ?? 'general';
                $action = end($nameParts); // استخراج آخر جزء من الاسم كـ action



                if (!isset($permissions[$group])) {
                    $permissions[$group] = [];
                }

                // التأكد من أن الإجراء غير مكرر
                if (!in_array($action, $permissions[$group], true)) {
                    $permissions[$group][] = $action;
                }
            }

            // إضافة الأذونات إلى بيانات الدور
            $roleData['permissions'] = $permissions;
        }

        return ApiResponse::apiResponse(
            JsonResponse::HTTP_OK,
            'Permission details',
            $roleData
        );
    } catch (\Exception $e) {
        return ApiResponse::apiResponse(
            JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
            'An error occurred',
            ['error' => $e->getMessage()]
        );
    }
}


}
