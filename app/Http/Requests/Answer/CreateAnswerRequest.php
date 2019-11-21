<?php

namespace App\Http\Requests\Answer;

use App\Http\Requests\Request;

class CreateAnswerRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'option.*' => 'required',
            'correct' => 'distinct|required|numeric|min:1|max:4',
            'question_id' => 'exists:questions,id'
        ];
    }
}
