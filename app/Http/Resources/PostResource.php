<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
                'title' => $this->title,
                'content' => $this->content,
                'image_url' => $this->image_url,
                'scheduled_time' => $this->scheduled_time,
                'status' => $this->status,
                'platforms' => $this->platforms,
                'date' => $this->date,
                'time' => $this->time,
            ];
    }
}
