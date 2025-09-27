<?php
namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    const TOKEN_NAME = 'token';

    // التعامل مع الـ Callback من Google
    public function login(Request $request)
    {
        try {
            $data = $request->validate([
                'social_id' => 'required',
                'first_name'      => 'nullable',
                'last_name'      => 'nullable',
                'email'     => 'nullable|email',
            ]);
            $user = User::where('social_id', $request->social_id)->first();
            if ($user) {
                $token = $user->createToken('self::TOKEN_NAME')->plainTextToken;
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'تم تسجيل الدخول بنجاح', [
                    'token' => $token,
                    'user'  => $user,
                ]);
            } else {
                $userData = [
                    'social_id'  => $data['social_id'],
                    'username'    => $data['first_name'] .  $data['last_name'],
                    'first_name'  => $data['first_name'] ?? 'غير محدد',
                    'last_name'   => $data['last_name'] ?? 'غير محدد',
                    'email'      => $data['email'] ?? null,
                    'user_type'  =>'student',
                    'password'   => Str::random(16), // يمكنك تخصيص كلمة مرور عشوائية هنا
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
                $user = User::create($userData);
                $token = $user->createToken('social-login')->plainTextToken;
                return ApiResponse::apiResponse(JsonResponse::HTTP_CREATED, 'تم إنشاء المستخدم بنجاح', [
                    'token' => $token,
                    'user'  => $user,
                ]);
            }

        } catch (\Exception $e) {
            // في حالة حدوث خطأ
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'حدث خطأ في تسجيل الدخول', $e->getMessage());
        }
    }
}
