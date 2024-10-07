<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
{
    public function toArray($request)
    {
        $productDetails = $this->product->productDetails ?? collect(); // Ensure it's a collection, even if null
    
        return [
            'id'           => $this->product->id,
            'name'         => $this->product->name,
            'description'  => $this->product->description,
            'discount'     => $this->product->discount,
            'quantity'     => $this->quantity,
            'size'         => $this->size,
            'attributes'   => $this->product->attributes,
            'product_details' => $productDetails->map(function ($detail) {
                return [
                    'id'         => $detail->id,
                    'product_id' => $detail->product_id,
                    'price'      => $detail->price,
                    'image'      => $detail->image ? url('storage/' . $detail->image) : null, 
                    'color'      => $detail->color,
                    'size'       => $detail->size,
                    'stock'      => $detail->stock,
                    'typeprice'  => $detail->typeprice,
                    'typeimage'  => $detail->typeimage ? url('storage/' . $detail->typeimage) : null, 
                    'typename'   => $detail->typename,
                    'typestock'  => $detail->typestock,
                    'created_at' => $detail->created_at,
                    'updated_at' => $detail->updated_at,
                ];
            }),
        ];
    }
}
