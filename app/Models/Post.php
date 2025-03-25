<?php

namespace App\Models;

use Carbon\Carbon;
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
        return Carbon::parse($this->scheduled_time);
    }

    public function isPublished()
    {
        return $this->status === 'published';
    }

    public function isScheduled()
    {
        return $this->status === 'scheduled';
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function scopeWhereScheduledAndPastScheduledTime($query)
    {
        $currentDateTime = Carbon::now()->utc()->toIso8601String();; // Current time in UTC
        return $query->where('status', 'scheduled')->where('scheduled_time', '<=', $currentDateTime);
    }

    public function scopeWhereAuthenticatedUser($query)
    {
        return $query->where('user_id', auth()->guard('sanctum')->user()->id);
    }


    public function isAuthorized()
    {
        return $this->user_id == auth()->guard('sanctum')->user()->id;
    }

    public function getDateAttribute()
    {
        $dateTime = Carbon::parse($this->scheduled_time)->setTimezone('GMT+2');
        return $dateTime->format('Y-m-d');
    }

    public function getTimeAttribute()
    {
        $dateTime = Carbon::parse($this->scheduled_time)->setTimezone('GMT+2');
        return $dateTime->format('H:i A');
    }
    public function scopeWhereStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeWhereDate($query, $date)
    {
        $date = Carbon::parseDate($date);
        return $query->where('scheduled_time', '>=', $date);
    }
}
