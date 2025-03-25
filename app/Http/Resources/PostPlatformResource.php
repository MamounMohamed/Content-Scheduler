<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostPlatformResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return
            [
                'id' => $this->id,
                'name' => $this->platform->name,
                'post_id' => $this->post_id,
                'post_title' => $this->post->title,
                'platform_id' => $this->platform_id,
                'is_active' => $this->is_active,
            ];
    }
}