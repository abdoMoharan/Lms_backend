<?php
namespace App\Http\Requests\Api\Profile;

use App\Http\Requests\Base\ApiRequest;
use Illuminate\Support\Facades\Auth;

class ProfileRequest extends ApiRequest
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
        'first_name'                     => 'required|string|max:100',
        'last_name'                      => 'required|string|max:100',
        'email'                          => 'required|string|email|max:255|unique:users,email,' . Auth::user()->id,
        'image'                          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'phone'                          => 'nullable|string|max:20|unique:users,phone,' . Auth::user()->id,
        'qualification'                  => 'nullable|string',
        'certificate_name'               => 'nullable|string',
        'certificate_date'               => 'nullable|date',
        'experience'                     => 'nullable|string',
        'id_card_number'                 => 'nullable|string',
        'id_card_image_front'            => 'nullable|image',
        'id_card_image_back'             => 'nullable|image',
        'birthdate'                      => 'nullable|date',
        'nationality'                    => 'nullable|string',
        'address'                        => 'nullable|string',
        'degree'                         => 'nullable|string',
        'cv'                             => 'nullable|file|mimes:pdf,doc,docx',
        'bio'                            => 'nullable|string',
        'gender'                         => 'nullable|string|in:male,female',
        'intro_video'                    => 'nullable',
        'educational_stages'             => 'nullable|array',
        'educational_stages.*.stage_id'  => 'nullable|exists:educational_stages,id',
        'subject_id'                     => 'nullable|exists:subjects,id',
    ];
}


    public function getData()
    {
        $data = $this->validated();
        return $data;
    }
}
