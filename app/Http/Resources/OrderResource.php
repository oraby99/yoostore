<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'user_id'        => $this->user_id,
            'address_id'     => $this->address_id,
            'invoice_id'     => $this->invoice_id,
            'total_price'    => $this->total_price,
            'status'         => $this->status,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
            'products'       => OrderProductResource::collection($this->products),
            'product_details' => $this->productDetails->map(function ($detail) {
                return [
                    'id'         => $detail->id,
                    'product_id' => $detail->product_id,
                    'price'      => $detail->price,
                    'image'      => $detail->image ? url('storage/' . $detail->image) : null, // Full URL for the image
                    'color'      => $detail->color,
                    'size'       => $detail->size, // Ensure this returns a proper value
                    'stock'      => $detail->stock,
                    'typeprice'  => $detail->typeprice,
                    'typeimage'  => $detail->typeimage ? url('storage/' . $detail->typeimage) : null, // Full URL for type image
                    'typename'   => $detail->typename,
                    'typestock'  => $detail->typestock,
                    'created_at' => $detail->created_at,
                    'updated_at' => $detail->updated_at,
                ];
            })
        ];
    }
    
}
