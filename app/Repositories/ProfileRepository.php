<?php
namespace App\Repositories;



use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

use App\Interfaces\ProfileInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Profile\ProfileResource;

class ProfileRepository implements ProfileInterface
{
    public function index()
    {
        $user = Auth::user();
        $user->load('roles');
        return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Profile retrieved successfully', new ProfileResource($user));
    }
    public function update($request)
    {
        try {
            DB::beginTransaction();
            $data = $request->getData();
            $user = Auth::user();
            $user->update($data);
            DB::commit();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Profile updated successfully', new ProfileResource($user));
        } catch (\Exception $e) {
            DB::rollback();
            return ApiResponse::apiResponse(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }
    public function changePassword($request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:8|confirmed',
        ]);
        if ($validator->fails()) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_BAD_REQUEST, $validator->errors());
        }
        $user = Auth::user();
        if (! Hash::check($request->current_password, $user->password)) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_BAD_REQUEST, 'Current password is incorrect');
        }
        $user->password = $request->new_password;
        $user->save();
        return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Password changed successfully');
    }
}
