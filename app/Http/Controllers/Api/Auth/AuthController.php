<?php
namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    const TOKEN_NAME = 'token';
    public User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function login(LoginRequest $request)
    {
        $contact  = $request->input('contact');
        $password = $request->input('password');

        $model = filter_var($contact, FILTER_VALIDATE_EMAIL)
        ? $this->model->where('email', $contact)->where('status', 1)->where('user_type','admin')->first()
        : $this->model->where('phone', $contact)->where('status', 1)->where('user_type','admin')->first();
        if (! $model) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_UNAUTHORIZED, 'Incorrect login details', []);
        }
        if ($model->status != 1) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_FORBIDDEN, 'This user is not allowed to log in', []);
        }
        if (! Hash::check($password, $model->password)) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_UNAUTHORIZED, 'Incorrect login details', []);
        }
        $token       = $model->createToken(self::TOKEN_NAME)->plainTextToken;
        $roles       = $model->roles->pluck('name');
        $permissions = [];
        foreach ($model->roles as $role) {
            foreach ($role->permissions as $perm) {
                $section   = $perm->section_name ?: 'general';
                $nameParts = explode('.', $perm->name);

                // تحقق إذا كان اسم المجموعة يحتوي على 'api.' في بدايته
                if (strpos($perm->name, 'admin.') === 0) {
                    // إذا كان يحتوي على 'api.'، نأخذ الجزء الذي بعد 'api.'
                    $group = $nameParts[1] ?? 'general';
                } else {
                    // إذا لم يحتوي على 'api.'، نأخذ أول جزء كـ group
                    $group = $nameParts[0] ?? 'general';
                }
                $action = end($nameParts); // يأخد آخر جزء بعد النقطة

                if (! isset($permissions[$group])) {
                    $permissions[$group] = [];
                }

                if (! in_array($action, $permissions[$group], true)) {
                    $permissions[$group][] = $action;
                }
            }
        }
        $permissions = $permissions;
        $response    = [
            'token' => $token,
            'user'  => [
                'id'          => $model->id,
                'username'    => $model->username,
                'email'       => $model->email,
                'phone'       => $model->phone,
                'user_type'   => $model->user_type,
                'roles'       => $roles,
                'permissions' => $permissions,
            ],
        ];

        return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'You have successfully logged in.', $response);
    }

    public function logout(Request $request): JsonResponse
    {

        $user = $request->user()->tokens->each->delete();

        return ApiResponse::apiResponse(
            JsonResponse::HTTP_OK,
            'Successfully logged out',
            []
        );
    }
}
