<?php
namespace App\Http\Requests\Api\Teacher\Auth;

use App\Http\Requests\Base\ApiRequest;
use Illuminate\Support\Facades\Auth;

class RegisterRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name'                    => 'required|string|max:100',
            'last_name'                     => 'required|string|max:100',
            'password'                      => 'required',
            'email'                         => 'required|string|email|max:255|unique:users,email',
            'image'                         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'phone'                         => 'required|string|max:20|unique:users,phone',
            'qualification'                 => 'required|string',
            'certificate_name'              => 'required|string',
            'certificate_date'              => 'required|date',
            'experience'                    => 'required|string',
            'id_card_number'                => 'required|string|unique:profiles,id_card_number',
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
            'educational_stages'            => 'required|array',
            'educational_stages.*.stage_id' => 'required|exists:educational_stages,id',
            'grads'                         => 'required|array',
            'grads.*.grad_id'               => 'required|exists:grades,id',
            'subject_id'                    => 'required|exists:subjects,id',
        ];
    }

    public function getData()
    {
        $data              = $this->validated();
        $data['user_type'] = 'teacher';
        return $data;
    }
}
