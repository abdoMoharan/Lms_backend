<?php
namespace App\Http\Requests\Api\GroupRegister;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Base\ApiRequest;

class GroupRegisterRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        $rules = [];
        $rules = array_merge($rules, [
            'group_id' => 'required|exists:groups,id',
            'price'    => 'required',
        ]);
        return $rules;
    }

    public function getData()
    {
        $data            = $this->validated();
        $data['user_id'] = Auth::user()->id;
        return $data;
    }
}
