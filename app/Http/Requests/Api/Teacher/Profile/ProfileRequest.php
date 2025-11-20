<?php
namespace App\Http\Requests\Api\Teacher\Profile;

use App\Http\Requests\Base\ApiRequest;

class ProfileRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user      = $this->user();
        $userId    = $user?->id;
        $profileId = $user?->profile?->id;

        return [
            'first_name'                    => 'required|string|max:100',
            'last_name'                     => 'required|string|max:100',

            // تجاهل نفس المستخدم في الـ unique
            'email'                         => 'required|string|email|max:255|unique:users,email,' . $userId,
            'phone'                         => 'required|string|max:20|unique:users,phone,' . $userId,

            'image'                         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            'qualification'                 => 'required|string',
            'certificate_name'              => 'required|string',
            'certificate_date'              => 'required|date',
            'experience'                    => 'required|string',

            // تجاهل نفس البروفايل في الـ unique
            'id_card_number'                => 'required|string|unique:profiles,id_card_number,' . $profileId,
            'id_card_image_front'           => 'nullable|image',
            'id_card_image_back'            => 'nullable|image',

            'birthdate'                     => 'required|date',
            'nationality'                   => 'nullable|string',
            'address'                       => 'nullable|string',
            'degree'                        => 'required|string',
            'cv'                            => 'nullable|file|mimes:pdf,doc,docx',
            'bio'                           => 'nullable|string',
            'gender'                        => 'required|string|in:male,female',
            'intro_video'                   => 'nullable',

            // العلاقة التعليميّةs
            'educational_stages'            => 'required|array|min:1',
            'educational_stages.*.stage_id' => 'required|exists:educational_stages,id',

            'grads'                         => 'required|array|min:1',
            'grads.*.grad_id'               => 'required|exists:grades,id',

            'subject_id'                    => 'required|exists:subjects,id',
        ];
    }

    /**
     * رجّع الداتا بشكل منظّم عشان الكنترولر
     */
    public function getData(): array
    {
        $validated = $this->validated();

        return [
            'user'               => [
                'first_name' => $validated['first_name'],
                'last_name'  => $validated['last_name'],
                'email'      => $validated['email'],
                'phone'      => $validated['phone'],
            ],
            'profile'            => [
                'qualification'       => $validated['qualification'],
                'certificate_name'    => $validated['certificate_name'],
                'certificate_date'    => $validated['certificate_date'],
                'experience'          => $validated['experience'],
                'id_card_number'      => $validated['id_card_number'],
                'birthdate'           => $validated['birthdate'],
                'nationality'         => $validated['nationality'] ?? null,
                'address'             => $validated['address'] ?? null,
                'degree'              => $validated['degree'],
                'bio'                 => $validated['bio'] ?? null,
                'gender'              => $validated['gender'],
                'intro_video'         => $validated['intro_video'] ?? null,
                'image'               => $validated['image'] ?? null,
                'cv'                  => $validated['cv'] ?? null,
                'id_card_image_front' => $validated['id_card_image_front'] ?? null,
                'id_card_image_back'  => $validated['id_card_image_back'] ?? null,
            ],
            // سيب الحقول الميديا إلى الميثود uploadFiles
            // TODO: ضع هنا منطق رفع الملفات حسب طريقتك في الـ upload
            'educational_stages' => $validated['educational_stages'],
            'grads'              => $validated['grads'],
            'subject_id'         => $validated['subject_id'],
        ];
    }
}
