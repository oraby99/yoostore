<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->product->id,
            'name' => $this->product->name,
            'description' => $this->product->description,
            'discount' => $this->product->discount,
            'quantity' => $this->quantity,
            'size' => $this->size,
            'product_details' => [
                'id' => $this->productDetail->id,
                'price' => $this->productDetail->price,
                'image' => $this->productDetail->image ,
                'color' => $this->productDetail->color,
                'size' => $this->productDetail->size,
                'typeprice' => $this->productDetail->typeprice,
                'typeimage' => $this->productDetail->typeimage ,
                'typename' => $this->productDetail->typename,
                'created_at' => $this->productDetail->created_at,
                'updated_at' => $this->productDetail->updated_at,
            ]
        ];
    }
}
