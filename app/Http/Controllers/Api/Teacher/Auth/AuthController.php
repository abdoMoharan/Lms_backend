<?php
namespace App\Http\Controllers\Api\Teacher\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Teacher\Auth\RegisterRequest;
use App\Models\Profile;
use App\Models\User;
use App\Models\UserEducationalStage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    const TOKEN_NAME = 'token';

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

            $profile = Profile::create([
                'user_id'             => $user->id,
                'qualification'       => $data['qualification'],
                'certificate_name'    => $data['certificate_name'],
                'certificate_date'    => $data['certificate_date'],
                'experience'          => $data['experience'],
                'id_card_number'      => $data['id_card_number'],
                'id_card_image_front' => $data['id_card_image_front'] ?? null,
                'id_card_image_back'  => $data['id_card_image_back'] ?? null,
                'birthdate'           => $data['birthdate'],
                'nationality'         => $data['nationality'] ?? null,
                'address'             => $data['address'] ?? null,
                'degree'              => $data['degree'],
                'cv'                  => $data['cv'] ?? null,
                'bio'                 => $data['bio'] ?? null,
                'gender'              => $data['gender'],
                'intro_video'         => $data['intro_video'] ?? null,
            ]);

            foreach ($data['educational_stages'] as $stage) {
                UserEducationalStage::create([
                    'user_id'    => $user->id,
                    'stage_id'   => $stage['stage_id'],
                    'subject_id' => $data['subject_id'],
                ]);
            }

            DB::commit();

            $token = $user->createToken(self::TOKEN_NAME)->plainTextToken;

            return ApiResponse::apiResponse(
                JsonResponse::HTTP_CREATED,
                [
                    'message'        => 'تم إنشاء الحساب بنجاح',
                    self::TOKEN_NAME => $token,
                    'user'           => $user,
                    'profile'        => $profile,
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

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:8',
        ]);
        $user = User::where('email', $request->email)
            ->where('user_type', 'teacher')
            ->first();
        if (! $user) {
            return ApiResponse::apiResponse(
                JsonResponse::HTTP_BAD_REQUEST,
                ['email' => ['البريد الإلكتروني غير مسجل']]
            );
        }
        if (! Hash::check($request->password, $user->password)) {
            return ApiResponse::apiResponse(
                JsonResponse::HTTP_BAD_REQUEST,
                ['password' => ['كلمة المرور غير صحيحة']]
            );
        }
        // إنشاء توكن جديد باستخدام Sanctum
        $token = $user->createToken(self::TOKEN_NAME)->plainTextToken;
        return ApiResponse::apiResponse(
            JsonResponse::HTTP_OK,
            [
                'message'        => 'تم تسجيل الدخول بنجاح',
                self::TOKEN_NAME => $token,
                'user'           => $user,
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
}
