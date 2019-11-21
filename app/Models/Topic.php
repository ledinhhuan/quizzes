<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    const IS_DISABLED = 0;
    const IS_ENABLED = 1;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'status'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
