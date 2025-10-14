<?php
namespace App\Http\Requests\Api\Group;

use App\Http\Requests\Base\ApiRequest;

class GroupRequest extends ApiRequest
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
            'group_name' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:users,id',
            'max_seats' => 'required|integer|min:1',
            'available_seats' => 'required|integer|min:0',
            'status' => 'nullable|in:0,1',
            'start_time' => 'required',
            'session_time' => 'required|in:pm,am',
            'session_status' => 'required|in:upcoming,ongoing,completed',
            'group_type' => 'required|in:individual,group',
            'hours_count' => 'required|integer|min:1',
        ];
    }


function getData() : array {
    return $this->validated();
}

}
