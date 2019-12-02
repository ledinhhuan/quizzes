<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAction extends Model
{
    use SoftDeletes;

    protected $fillable = ['action', 'action_model', 'action_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setUserIdAttribute($input)
    {
        if ($input != null) {
            $this->attributes['user_id'] = $input;
        }
    }
}
