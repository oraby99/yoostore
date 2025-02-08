<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    protected $userId;

    public function __construct($resource, $userId = null)
    {
        parent::__construct($resource);
        $this->userId = $userId;
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->getTranslations('name'),
            'image' => $this->image,
            'tag' => $this->getTranslations('tag'),
            'bannertag' => $this->getTranslations('bannertag'),
            'bannerimage' => $this->bannerimage,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'products' => ProductResource::collection($this->products)->additional(['user_id' => $this->userId]),
        ];
    }
}