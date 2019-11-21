<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\Request;

class CreateUserRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|min:6|max:255|unique:users',
            'password' => 'required|min:6|max:255|confirmed',
        ];
    }
}
