<?php
namespace App\Http\Requests\Api\Answer;

use App\Http\Requests\Base\ApiRequest;

class AnswerRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $req = [];
        $req = array_merge($req, [
            'name'           => 'required',
            'correct_answer' => 'nullable|in:1,0',
            'question_id'    => 'required|exists:questions,id',
        ]);

        return $req;
    }

    public function getData()
    {
        $data = $this->validated();
        return $data;
    }

}
