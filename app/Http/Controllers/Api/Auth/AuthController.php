<?php
namespace App\Http\Controllers\Api\Admin\Auth;

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

        $model = null;
        if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            $model =$this->model->where('email', $contact)->first();
        } else {
            $model = $this->model->where('phone', $contact)->first();
        }
        if (! $model || ! Hash::check($password, $model->password)) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_UNAUTHORIZED, 'Incorrect login details', []);
        }
        $token = $model->createToken(self::TOKEN_NAME)->plainTextToken;
        return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'You have successfully logged in.', ['token' => $token]);
    }
}
