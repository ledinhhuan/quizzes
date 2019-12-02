<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Test extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'result', 'topic_id'];

    public static function boot()
    {
        parent::boot();
        self::observe(new \App\Observers\UserActionObserver);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public static function dataQuestions(Request $request, $questions, $test)
    {
        $data = [];
        if (is_array($questions) && count($questions)) {
            foreach ($questions as $key => $question)
            {
                $answer = $request->input('answers.' . $key);
                $data[] = [
                    'test_id' => $test['id'],
                    'question_id' => $question,
                    'answer_id' =>  $answer,
                    'created_at' => \now(),
                    'updated_at' => \now(),
                ];
            }
        }

        return $data;
    }

    /**
     * Calculate Result when submit
     *
     * @param $answers
     * @return int
     */
    public static function calculateResult($answers)
    {
        $result = 0;
        if (!is_null($answers) && count($answers)) {
            foreach ($answers as $answer)
            {
                $result = $answer->isCorrect() ? $result + 1 : $result;
            }
        }

        return $result;
    }
}
