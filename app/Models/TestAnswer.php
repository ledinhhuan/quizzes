<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestAnswer extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'test_id', 'question_id', 'option_id', 'correct'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
