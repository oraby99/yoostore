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
            'image' => $this->image ,
            'banner' => $this->banner ,
            'tag' => $this->tag,
            'bannerimage' => $this->bannerimage ,
            'subcategories' => SubCategoryResource::collection($this->whenLoaded('subcategories')),
        ];
    }
}
