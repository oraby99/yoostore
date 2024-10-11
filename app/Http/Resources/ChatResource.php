<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
            'message' => $this->message,
            'reply'   => $this->reply,
            'created_at' => $this->created_at,
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
