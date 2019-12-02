<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    const NUM_OF_QUESTION = 10;

    use SoftDeletes;

    protected $fillable = [
        'question_text', 'code_snippet', 'answer_explanation', 'more_info_link', 'topic_id'
    ];

    public static function boot()
    {
        parent::boot();
        self::observe(new \App\Observers\UserActionObserver);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
