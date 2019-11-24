<?php

namespace App\Http\Requests\Tests;

use App\Http\Requests\Request;

class CreateTestRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'questions.*' => 'required',
            'answers.*' => 'required'
        ];
    }
}
