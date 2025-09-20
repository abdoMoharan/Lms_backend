<?php
namespace App\Http\Requests\Api\Question;

use App\Http\Requests\Base\ApiRequest;
use Illuminate\Support\Facades\Auth;


class QuestionRequest extends ApiRequest
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
'name'=>'required',
            'status'           => 'nullable|in:1,0',
            'question_type_id' => 'required|exists:question_types,id',
            'exam_id'          => 'required|exists:exams,id',
        ]);

        return $req;
    }

    public function getData()
    {
        $data = $this->validated();
        if ($this->isMethod('POST')) {
            $data['created_by'] = Auth::user()->id;
        } else {
            $data['updated_by'] = Auth::user()->id;
        }
        return $data;
    }

}
