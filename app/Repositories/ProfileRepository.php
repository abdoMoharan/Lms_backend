<?php
namespace App\Repositories;

use App\Helpers\ApiResponse;
use App\Http\Resources\Profile\ProfileResource;
use App\Interfaces\ProfileInterface;
use App\Models\Profile;
use App\Models\UserEducationalStage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

            if ($user->user_type == 'teacher') {
                // محاولة العثور على الـ Profile الحالي
                $profile = Profile::where('user_id', $user->id)->first();
                // إذا لم يكن موجودًا، أنشئ الـ Profile
                if (! $profile) {
                    $profile          = new Profile();
                    $profile->user_id = $user->id;
                }
                // تحديث بيانات الـ Profile
                $profile->qualification       = $data['qualification'];
                $profile->certificate_name    = $data['certificate_name'];
                $profile->certificate_date    = $data['certificate_date'];
                $profile->experience          = $data['experience'];
                $profile->id_card_number      = $data['id_card_number'];
                $profile->id_card_image_front = $data['id_card_image_front'];
                $profile->id_card_image_back  = $data['id_card_image_back'];
                $profile->birthdate           = $data['birthdate'];
                $profile->nationality         = $data['nationality'];
                $profile->address             = $data['address'];
                $profile->degree              = $data['degree'];
                $profile->cv                  = $data['cv'];
                $profile->bio                 = $data['bio'];
                $profile->gender              = $data['gender'];
                $profile->intro_video         = $data['intro_video'];
                $profile->save();
                if ($request->educational_stages) {
                    foreach ($data['educational_stages'] as $stage) {
                        $user_eduction_stage             = new UserEducationalStage();
                        $user_eduction_stage->user_id    = $user->id;
                        $user_eduction_stage->stage_id   = $stage->stage_id;
                        $user_eduction_stage->subject_id = $data['subject_id'];
                        $user_eduction_stage->save();
                    }
                }
            }
            $profile->load('user');
            DB::commit();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Profile updated successfully', new ProfileResource($profile));
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
