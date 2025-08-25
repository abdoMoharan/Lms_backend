<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Validation\Rule;
use App\Http\Requests\Base\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Email; // أضف هذا السطر

class UserRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userID = null;
        $method = $this->getMethod();
        if ($method == 'PUT') {
            $routeUser = $this->route('user');
            $userID = is_object($routeUser) ? $routeUser->id : $routeUser;
        }
        return [
            'username' => 'required|string|max:50',
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'user_type'  => 'nullable|in:student,teacher,admin',
            'email' => [
                'required',
                Rule::email()                             // يبني قاعدة تحقق ديناميكية للبريد
                    ->rfcCompliant(strict: false)          // يتحقق من الامتثال الجزئي لمعيار RFC 5322
                    ->validateMxRecord()                   // يتأكد من وجود سجلات MX للدومين
                    ->preventSpoofing(),                   // يمنع عناوين البريد المزيفة
                Rule::unique('users', 'email')            // يتحقّق من تفرد البريد في جدول users
                    ->ignore($userID),                    // يتجاهل السجل الحالي عند التحديث
            ],
            'password'   => $userID ? 'nullable|min:8' : 'required|min:8',
            'phone'      => [
                'nullable',
                Rule::unique('users', 'phone')->ignore($userID),
            ],
            'status'     => 'nullable|in:1,0',
            'roles'      => 'required|exists:roles,name',
        ];
    }

    public function getData()
    {
        $data = $this->validated();
        return $data;
    }
}
