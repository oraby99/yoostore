<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->getTranslations('name'),
            'image' => $this->image ? url('storage/' . $this->image) : null,
            'tag' => $this->getTranslations('tag'),
            'bannertag' => $this->getTranslations('bannertag'),
            'bannerimage' => $this->bannerimage ? url('storage/' . $this->bannerimage) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'products' => ProductResource::collection($this->products),
        ];
    }
}

