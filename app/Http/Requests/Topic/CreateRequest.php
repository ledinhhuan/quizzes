<?php

namespace App\Http\Requests\Topic;

use App\Http\Requests\Request;

class CreateRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:3|max:255'
        ];
    }
}
