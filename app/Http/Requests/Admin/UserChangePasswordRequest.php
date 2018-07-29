<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class UserChangePasswordRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password_original' =>'required|between:6,12',
            'password_new' =>'required|between:6,12',
            'password_confirm' =>'required|between:6,12'
        ];
    }

    public function messages() {
        return [
            'password_original.required' => '原始密码是必填的',
            'password_new.required' => '新密码密码是必填的',
            'password_confirm.required' => '确认密码是必填的',
            'password_original.between' => '原始密码由6-12位组成',
            'password_new.between' => '新密码密码由6-12位组成',
            'password_confirm.between' => '确认密码由6-12位组成'
        ];
    }
}
