<?php
namespace App\Http\Requests\Api\Course;

use App\Http\Requests\Base\ApiRequest;

class CoursePriceRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        $rules = [];
        $rules = array_merge($rules, [
            'stage_id'  => 'required|exists:educational_stages,id',
            'grade_id'  => 'required|exists:grades,id',
            'course_id' => 'required|exists:courses,id',
            'price'     => 'required',
        ]);
        return $rules;
    }

    public function getData()
    {
        $data = $this->validated();
        return $data;
    }
}
