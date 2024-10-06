<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'           => $this->product->id,
            'name'         => $this->product->name,
            'description'  => $this->product->description,
            'discount'     => $this->product->discount,
            'quantity'     => $this->quantity,
            'size'         => $this->size,
            'attributes'   => $this->product->attributes,
        ];
    }
}
