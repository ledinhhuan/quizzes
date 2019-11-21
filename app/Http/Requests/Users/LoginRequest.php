<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\Request;

class LoginRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|min:6|max:255',
            'password' => 'required|min:6|max:255',
        ];
    }
}
