<?php

namespace App\Http\Controllers\Api\Teacher\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Teacher\Auth\RegisterRequest;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    const TOKEN_NAME = 'token';

    /**
     * دالة لإعادة إرسال OTP
     */
    public function resendOtp(Request $request)
    {
        // التحقق من وجود البريد الإلكتروني
        $request->validate([
            'email' => 'required|email',
        ]);

        // البحث عن المستخدم باستخدام البريد الإلكتروني
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return ApiResponse::apiResponse(
                JsonResponse::HTTP_BAD_REQUEST,
                ['email' => ['البريد الإلكتروني غير مسجل']]
            );
        }

        // التحقق إذا كانت صلاحية OTP قد انتهت أو أن الكود غير صالح
        if ($user->otp_expires_at && now()->gt($user->otp_expires_at)) {
            // مسح الكود القديم إذا كانت صلاحية OTP قد انتهت
            $user->otp = null;
            $user->otp_expires_at = null;
            $user->otp_sent_at = null; // مسح وقت الإرسال القديم
            $user->save();
        }

        // تحقق من الوقت بين إرسال OTP السابق وإذا كان أقل من 30 ثانية أو دقيقة
        if ($user->otp_sent_at && $user->otp_sent_at->gt(now()->subSeconds(30))) {
            return ApiResponse::apiResponse(
                JsonResponse::HTTP_BAD_REQUEST,
                ['error' => ['يجب الانتظار 30 ثانية قبل محاولة إرسال OTP آخر']]
            );
        }

        // توليد OTP عشوائي وتخزينه
        $otp = rand(100000, 999999);
        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(10);  // صلاحية الكود 10 دقائق
        $user->otp_sent_at = now();  // تحديث وقت إرسال OTP
        $user->save();

        // إرسال OTP إلى البريد الإلكتروني
        Mail::to($user->email)->send(new OtpMail($otp));

        return ApiResponse::apiResponse(
            JsonResponse::HTTP_OK,
            [
                'message' => 'تم إرسال OTP جديد إلى بريدك الإلكتروني للتحقق',
                'user'    => $user,
            ]
        );
    }

    /**
     * دالة التسجيل
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        // تحقق من عدم تكرار البريد أو الهاتف أو رقم الهوية
        if (User::where('email', $data['email'])->exists()) {
            return ApiResponse::apiResponse(
                JsonResponse::HTTP_BAD_REQUEST,
                ['email' => ['البريد مسجل مسبقاً']]
            );
        }

        if (User::where('phone', $data['phone'])->exists()) {
            return ApiResponse::apiResponse(
                JsonResponse::HTTP_BAD_REQUEST,
                ['phone' => ['رقم الجوال مسجل مسبقاً']]
            );
        }

        if (\App\Models\Profile::where('id_card_number', $data['id_card_number'])->exists()) {
            return ApiResponse::apiResponse(
                JsonResponse::HTTP_BAD_REQUEST,
                ['id_card_number' => ['رقم الهوية مسجل مسبقاً']]
            );
        }

        try {
            DB::beginTransaction();
            $user = User::create([
                'username'   => $data['first_name'] . $data['last_name'],
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'phone'      => $data['phone'],
                'password'   => $data['password'],
                'status'     => 0,
                'user_type'  => 'teacher',
            ]);

            // توليد OTP عشوائي
            $otp                  = rand(100000, 999999);
            $user->otp            = $otp;
            $user->otp_expires_at = now()->addMinutes(10); // صلاحية الكود 10 دقائق
            $user->otp_sent_at    = now(); // تحديث وقت إرسال OTP
            $user->save();

            // إرسال OTP عبر البريد الإلكتروني
            Mail::to($user->email)->send(new OtpMail($otp));

            DB::commit();

            return ApiResponse::apiResponse(
                JsonResponse::HTTP_CREATED,
                [
                    'message' => 'تم إنشاء الحساب بنجاح. تم إرسال OTP إلى بريدك الإلكتروني للتحقق',
                    'user'    => $user,
                ]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::apiResponse(
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                ['error' => [$e->getMessage()]]
            );
        }
    }

    /**
     * دالة لتسجيل الدخول
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)
            ->where('user_type', 'teacher')
            ->first();

        if (!$user) {
            return ApiResponse::apiResponse(
                JsonResponse::HTTP_BAD_REQUEST,
                ['email' => ['البريد الإلكتروني غير مسجل']]
            );
        }

        // تحقق من الوقت بين إرسال OTP السابق وإذا كان أقل من 30 ثانية أو دقيقة
        if ($user->otp_sent_at && $user->otp_sent_at->gt(now()->subSeconds(30))) {
            return ApiResponse::apiResponse(
                JsonResponse::HTTP_BAD_REQUEST,
                ['error' => ['يجب الانتظار 30 ثانية قبل محاولة إرسال OTP آخر']]
            );
        }

        // توليد OTP عشوائي وتخزينه
        $otp                  = rand(100000, 999999);
        $user->otp            = $otp;
        $user->otp_expires_at = now()->addMinutes(10); // صلاحية الكود 10 دقائق
        $user->otp_sent_at    = now(); // تحديث وقت إرسال OTP
        $user->save();

        // إرسال OTP إلى البريد الإلكتروني
        Mail::to($user->email)->send(new OtpMail($otp));

        return ApiResponse::apiResponse(
            JsonResponse::HTTP_OK,
            [
                'message' => 'تم إرسال OTP إلى بريدك الإلكتروني للتحقق',
                'user'    => $user,
            ]
        );
    }

    /**
     * تسجيل الخروج للمعلم
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            // حذف جميع التوكنات الخاصة بالمستخدم
            $user->tokens()->delete();

            return ApiResponse::apiResponse(
                JsonResponse::HTTP_OK,
                ['message' => 'تم تسجيل الخروج بنجاح']
            );
        }

        return ApiResponse::apiResponse(
            JsonResponse::HTTP_BAD_REQUEST,
            ['error' => ['لا يوجد مستخدم مسجل دخول']]
        );
    }

    /**
     * دالة للتحقق من OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp'   => 'required|numeric|digits:6',
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && $user->otp == $request->otp && now()->lt($user->otp_expires_at)) {
            // التحقق ناجح
            $user->otp = null; // مسح OTP بعد التحقق
            $user->save();

            // إنشاء توكن جديد باستخدام Sanctum
            $token = $user->createToken(self::TOKEN_NAME)->plainTextToken;

            return ApiResponse::apiResponse(
                JsonResponse::HTTP_OK,
                [
                    'message'        => 'تم التحقق من OTP بنجاح',
                    self::TOKEN_NAME => $token,
                    'user'           => $user,
                ]
            );
        }

        return ApiResponse::apiResponse(
            JsonResponse::HTTP_BAD_REQUEST,
            ['otp' => ['رمز التحقق غير صحيح أو انتهت صلاحيته']]
        );
    }
}
