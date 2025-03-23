<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;
    protected $table = 'posts';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'image_url',
        'scheduled_time',
        'status',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function platforms()
    {
        return $this->belongsToMany(Platform::class, 'post_platforms', 'post_id', 'platform_id');
    }

    public function scheduledTime()
    {
        return $this->scheduled_time;
    }

    public function isPublished()
    {
        return $this->effective_status === 'published';
    }

    public function isScheduled()
    {
        return $this->effective_status === 'scheduled';
    }

    public function isDraft()
    {
        return $this->effective_status === 'draft';
    }

    public function getEffectiveStatusAttribute()
    {
        if ($this->scheduledTime()->isPast() && $this->status === 'scheduled') {
            $this->status = 'published';
            $this->save();
        }
        return $this->status;
    }
}
