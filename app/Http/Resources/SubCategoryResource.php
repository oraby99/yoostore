<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
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
            'name' => $this->name,
            'image' => $this->image ? url('storage/' . $this->image) : null,
            'banner' => $this->banner ? url('storage/' . $this->banner) : null,
            'category_id' => $this->category_id,
            'bannertag' => $this->bannertag,
            'bannerimage' => $this->bannerimage ? url('storage/' . $this->bannerimage) : null,
        ];

    }
}
