<?php

use App\Models\CustomPermission;
use Illuminate\Support\Facades\Route;

//Get All Route And filter
function getAdminRoutes()
{
    $routeCollection = Route::getRoutes();
    $permissions = [];

    foreach ($routeCollection as $route) {
        $name = $route->getName();

        // نتأكد إن الراوت له اسم
        if (!$name) {
            continue;
        }

        $fullPrefix = $route->getAction('prefix') ?? 'general';
        $segments = explode('/', $fullPrefix);

        // حذف جزء 'api' من البداية لو موجود
        if (!empty($segments) && $segments[0] === 'api') {
            array_shift($segments); // يشيل أول عنصر
        }

        // استخراج أول جزء بعد 'api' كـ section
        $section = $segments[0] ?? 'general';

        $permissions[] = [
            'name' => $name,
            'section' => $section,
        ];
    }

    return $permissions;
}


function syncPermisions($model = null)
{
    $routes = getAdminRoutes();

    foreach ($routes as $route) {
        $routeName = $route['name'];
        $sectionName = $route['section'];
        $groupName = explode('.', $routeName)[0] ?? 'general';

        $permissionExist = (clone $model)->where('name', $routeName)->first();
        if ($permissionExist == null) {
            CustomPermission::create([
                'name' => $routeName,
                'trans_name' => $routeName,
                'group_name' => $groupName,
                'section_name' => $sectionName,
                'guard_name' => 'web',
            ]);
        }
    }
}


if (! function_exists('transPermission')) {
    function transPermission($val)
    {
        $val = str_replace('admin.', '', $val);
        $val = str_replace('.', ' ', $val);
        $val = str_replace('-', ' ', $val);
        return $val;
    }

}
