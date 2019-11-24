<?php

namespace App\Http\Requests\Question;

use App\Http\Requests\Request;
use App\Models\Question;
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
            'question_text' => [
                'required',
                'min:3',
                function ($attribute, $value, $fail) {
                    $question = Question::query()->where('question_text', $value)->first();
                    if ($question) {
                        $question = preg_replace('/\s+/', '', strtolower($question->question_text));
                        $questionMatch = preg_replace('/\s+/', '', strtolower($value));
                        if (strcmp($question, $questionMatch) == 0) {
                            return $fail(trans('validation.unique', [$attribute]));
                        }
                    }
                    return true;
                }
            ]
        ];
    }
}
