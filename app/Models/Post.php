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

    public function isAuthorized()
    {
        return $this->user_id == auth()->guard('sanctum')->user()->id;
    }

    public function getEffectiveStatusAttribute()
    {
        if ($this->scheduledTime()->isPast() && $this->status == 'scheduled') {
            $this->update([
                'status' => 'published',
            ]);
        }

        if ($this->scheduledTime()->isFuture() && $this->status == 'published') {
            $this->update([
                'status' => 'scheduled',
            ]);
        }
        $this->save();
        return $this->status;
    }
    public function getDateAttribute()
    {
        $dateTime = Carbon::parse($this->scheduled_time);
        return $dateTime->format('Y-m-d');
    }

    public function getTimeAttribute()
    {
        $dateTime = Carbon::parse($this->scheduled_time);
        return $dateTime->format('H:i');
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
