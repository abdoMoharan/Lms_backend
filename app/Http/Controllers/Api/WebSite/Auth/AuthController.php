<?php
namespace App\Http\Controllers\Api\WebSite\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    const TOKEN_NAME = 'token';
    public User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'phone'      => 'required|string|max:255|unique:users',
            'password'   => 'required|string|min:8|max:255',
        ]);

        try {
            DB::beginTransaction();
            $user = User::create([
                'username'   => $data['first_name'] . $data['last_name'],
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'phone'      => $data['phone'],
                'password'   => $data['password'],
                'status'     => 1,
                'user_type'  => 'student',
            ]);
            $token = $user->createToken(self::TOKEN_NAME)->plainTextToken;
            DB::commit();
            return ApiResponse::apiResponse(
                JsonResponse::HTTP_CREATED,
                [
                    'message' => 'تم إنشاء الحساب بنجاح',
                    'token'   => $token,
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

    public function login(LoginRequest $request)
    {
        $contact  = $request->input('contact');
        $password = $request->input('password');

        $model = filter_var($contact, FILTER_VALIDATE_EMAIL)
            ? $this->model->where('email', $contact)->where('status', 1)->where('user_type', 'student')->first()
            : $this->model->where('phone', $contact)->where('status', 1)->where('user_type', 'student')->first();
        if (! $model) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_UNAUTHORIZED, 'Incorrect login details', []);
        }
        if ($model->status != 1) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_FORBIDDEN, 'This user is not allowed to log in', []);
        }
        if (! Hash::check($password, $model->password)) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_UNAUTHORIZED, 'Incorrect login details', []);
        }
        $token    = $model->createToken(self::TOKEN_NAME)->plainTextToken;
        $response = [
            'token' => $token,
            'user'  => [
                'id'        => $model->id,
                'username'  => $model->username,
                'email'     => $model->email,
                'phone'     => $model->phone,
                'user_type' => $model->user_type,
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
