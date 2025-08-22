<?php
namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
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
        ? $this->model->where('email', $contact)->first()
        : $this->model->where('phone', $contact)->first();
        if (! $model) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_UNAUTHORIZED, 'Incorrect login details', []);
        }
        if ($model->status != 1) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_FORBIDDEN, 'This user is not allowed to log in', []);
        }
        if (! Hash::check($password, $model->password)) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_UNAUTHORIZED, 'Incorrect login details', []);
        }
        $token = $model->createToken(self::TOKEN_NAME)->plainTextToken;
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
}
