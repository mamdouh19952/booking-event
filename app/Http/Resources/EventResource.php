<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'available_seats' => $this->available_seats,
            'category_id' => $this->category_id,
            'category' => new CategoryResource($this->whenLoaded('category')),

            'image' => $this->getFirstMediaUrl('event_cover') ?: null,

            // 'images' => $this->getMedia('event_gallery')->map->getUrl(),

            'images_urls' => $this->getMedia('event_gallery')->map(function ($item) {
    return [
        'id' => $item->id,
        'url' => $item->getUrl(),
    ];
}),

        ];
    }
}
