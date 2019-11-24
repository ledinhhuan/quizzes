<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Topic extends Model
{
    const IS_DISABLED = 0;
    const IS_ENABLED = 1;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'status'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Set the name and the readable slug.
     *
     * @param string $value
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->setUniqueSlug($value, Str::random(10));
    }

    /**
     * Set the unique slug.
     *
     * @param $value
     * @param $extra
     */
    public function setUniqueSlug($value, $extra)
    {
        $slug = Str::slug($value . '-' . $extra);
        if (self::whereSlug($slug)->exists()) {
            $this->setUniqueSlug($slug, (int) $extra + 1);
            return;
        }

        $this->attributes['slug'] = $slug;
    }
}
