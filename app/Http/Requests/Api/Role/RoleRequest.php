<?php
namespace App\Http\Requests\Api\Role;

use App\Http\Requests\Base\ApiRequest;

class RoleRequest extends ApiRequest
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
            'name'          => 'required|string|max:255|unique:roles,name',
            'guard_name'          => 'required',
            'permissions'   => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'The name field is required.',
            'name.string'          => 'The name field must be a string.',
            'name.max'             => 'The name field must not be greater than 255 characters.',
            'name.unique'          => 'The name field has already been taken.',
            'permissions.required' => 'The permissions field is required.',
            'permissions.array'    => 'The permissions field must be an array.',
            'permissions.*.exists' => 'The selected permissions are invalid.',
        ];
    }

    public function getData()
    {
        $data = $this->validated();
        return $data;
    }
}
