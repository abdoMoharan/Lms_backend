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
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'password'   => 'required',
            'email'      => 'required|string|email|max:255|unique:users,email',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'phone'      => 'required|string|max:20|unique:users,phone',
        ];
    }

    public function getData()
    {
        $data              = $this->validated();
        $data['user_type'] = 'teacher';
        return $data;
    }
}
