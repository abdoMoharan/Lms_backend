<?php
namespace App\Http\Requests\Api\Teacher\Group;

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
            'group_name'      => 'required|string|max:255',
            'course_id'       => 'required|exists:courses,id',
            'max_seats'       => 'required|integer|min:1',
            'available_seats' => 'required|integer|min:0',
            'status'          => 'nullable|in:0,1',
            'start_date'      => 'required|date',
            'start_time'      => 'required',
            'session_time'    => 'required|in:pm,am',
            'session_status'  => 'required|in:scheduled,completed,cancelled',
            'group_type'      => 'required|in:individual,group',
            'hours_count'     => 'nullable|integer|min:1',
            'duration'        => 'nullable|integer|min:1',
            'week_ids'        => 'required',
            'week_ids.*'      => 'exists:weeks,id',
        ];
    }

    public function getData(): array
    {
        $data               = $this->validated();
        $data['teacher_id'] = auth()->user()->id;
        return $data;
    }
}
