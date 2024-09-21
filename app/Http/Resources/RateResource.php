<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'rate' => $this->rate,
            'title' => $this->title,
            'description' => $this->description,
            'images' => $this->images ? array_map(fn($image) => url($image), $this->images) : null,
            'date' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null, // Format the date
            'userName' => $this->user->name,
        ];
    }
}
