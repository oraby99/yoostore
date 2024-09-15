<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image ? url('storage/' . $this->image) : null,
            'banner' => $this->banner ? url('storage/' . $this->banner) : null,
            'tag' => $this->tag,
            'bannerimage' => $this->bannerimage ? url('storage/' . $this->bannerimage) : null,
            'subcategories' => SubCategoryResource::collection($this->whenLoaded('subcategories')),
        ];
    }
}
