<?php
namespace App\Http\Requests\Api\Profile;



use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Base\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

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
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::user()->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'phone' => 'nullable|string|max:20|unique:users,phone,'. Auth::user()->id,
        ];
    }

    public function getData()
    {
        $data = $this->validated();
        return $data;
    }
}
