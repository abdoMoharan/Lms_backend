<?php

namespace App\Http\Controllers\Api\Teacher\Profile;

use App\Models\User;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Teacher\Profile\ProfileRequest;

class ProfileController extends Controller
{
    public User $model;

    public function __construct(User $model)
    {
$this->model=$model->findOrFail(request()->user()->id);
    }


    public function update(ProfileRequest $request)
    {
        $data = $request->getData();
        $user = $this->model; // User المصادق عليه

        // 1) تحديث جدول users
        $user->update($data['user']);

        // 2) تجهيز بيانات البروفايل
        $profileData = $data['profile'];

        // 3) تحديث / إنشاء البروفايل
        if ($user->profile) {
            $user->profile->update($profileData);
        } else {
            $user->profile()->create($profileData);
        }

        // 4) تحديث مراحل / صفوف المدرّس
        if (!empty($data['educational_stages']) && !empty($data['grads'])) {

            // امسح القديم
            $user->userEductionStage()->delete();

            // أنشئ التركيبات (Stage × Grade) لنفس الـ subject
            foreach ($data['educational_stages'] as $stage) {
                foreach ($data['grads'] as $grad) {
                    $user->userEductionStage()->create([
                        'stage_id'   => $stage['stage_id'],   // لاحظ الاسم
                        'subject_id' => $data['subject_id'],
                        'grad_id'    => $grad['grad_id'],
                    ]);
                }
            }
        }

        // 5) رجّع بيانات محدثة
        $user->load(['profile', 'userEductionStage.educational_stage', 'userEductionStage.subject', 'userEductionStage.grade']);

          return ApiResponse::apiResponse(
            JsonResponse::HTTP_OK,
            [
                'update profile successfully',
                $user,
            ]
        );
    }
}

