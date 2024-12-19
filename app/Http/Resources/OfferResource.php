<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
            'banner' => $this->banner ,
            'bannertag' => $this->getTranslation('bannertag', 'en'),
            'tag' => $this->getTranslation('tag', 'en'),
            'secondbanner' => $this->secondbanner ,
        ];
    }
}
