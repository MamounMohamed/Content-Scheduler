<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PostPlatform extends Pivot
{
    /** @use HasFactory<\Database\Factories\PostPlatformFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'post_id',
        'platform_id',
        'is_active',
    ];

    protected $table = 'post_platforms';

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    public function post()
    {
        return $this->belongsTo(Post::class);

    }

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function isActive()
    {
        return $this->is_active === true;
    }

    public function isInactive()
    {
        return $this->is_active === false;
    }

}
