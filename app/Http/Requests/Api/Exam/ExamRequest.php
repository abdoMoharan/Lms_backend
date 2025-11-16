<?php
namespace App\Http\Requests\Api\Exam;

use App\Http\Requests\Base\ApiRequest;
use Illuminate\Support\Facades\Auth;

class ExamRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules()
    {
        return [
            'name'                              => 'required|string',
            'description'                       => 'nullable|string',
            'group_session_id'                  => 'required|exists:group_sessions,id',
            'duration'                          => 'required|integer',
            'start_date'                        => 'required|date',
            'end_date'                          => 'required|date',
            'total'                             => 'nullable',
            'questions'                         => 'required|array',
            'questions.*.question_text'         => 'required|string',
            'questions.*.mark'                  => 'required|integer',
            'questions.*.options'               => 'required|array|min:2',
            'questions.*.options.*.option_text' => 'required|string',
            'questions.*.options.*.is_correct'  => 'required|in:1,0',
        ];
    }

    public function getData()
    {
        $data               = $this->validated();

        return $data;
    }

}
