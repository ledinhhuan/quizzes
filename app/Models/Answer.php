<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    const CORRECT = 1;
    use SoftDeletes;

    protected $fillable = [
        'option', 'correct', 'question_id'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    public function isCorrect()
    {
        return $this->correct == self::CORRECT;
    }
}
