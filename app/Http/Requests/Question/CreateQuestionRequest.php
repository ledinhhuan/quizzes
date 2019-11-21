<?php

namespace App\Http\Requests\Question;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class CreateQuestionRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'topic_id' => 'required|exists:topics,id',
            'question_text' => 'required|min:3'
        ];
    }
}
