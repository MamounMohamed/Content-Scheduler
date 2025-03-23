<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    /** @use HasFactory<\Database\Factories\PlatformFactory> */
    use HasFactory;
    protected $table = 'platforms';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_platforms', 'platform_id', 'post_id');
    }

}
