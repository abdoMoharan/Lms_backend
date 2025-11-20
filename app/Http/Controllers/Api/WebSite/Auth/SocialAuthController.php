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

    // التعامل مع الـ Callback من Google أو Facebook
    public function login(Request $request)
    {
        try {
            // التحقق من البيانات الواردة
            $data = $request->validate([
                'email'      => 'nullable|email',
                'name'       => 'nullable',     // الاسم الكامل
                'provider'       => 'nullable',     // الاسم الكامل
            ]);
            // البحث عن المستخدم بناءً على الـ social_id
            $user = User::where('email', $request->email)->first();

            if ($user) {
                $token = $user->createToken(self::TOKEN_NAME)->plainTextToken;
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'تم تسجيل الدخول بنجاح', [
                    'token' => $token,
                    'user'  => $user,
                ]);
            } else {
                // إذا كان المستخدم غير موجود، نقوم بإنشائه
                $userData = [
                    'username'   => $data['name'], // دمج الاسم الأول والأخير
                    'first_name' => $data['name'] ?? 'غير محدد',
                    'email'      => $data['email'] ,
                    'provider'      => $data['provider'] ,
                    'user_type'  => 'student',
                    'password'   => Str::random(16), // كلمة مرور عشوائية
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
                $user  = User::create($userData);
                $token = $user->createToken('social-login')->plainTextToken;
                return ApiResponse::apiResponse(JsonResponse::HTTP_CREATED, 'تم إنشاء المستخدم بنجاح', [
                    'token' => $token,
                    'user'  => $user,
                ]);
            }

        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'حدث خطأ في تسجيل الدخول', $e->getMessage());
        }
    }
}
