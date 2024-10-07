<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'address_id' => $this->address_id,
            'invoice_id' => $this->invoice_id,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'products' => $this->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'discount' => $product->discount,
                    'quantity' => $product->pivot->quantity,  // From order_product pivot table
                    'size' => $product->pivot->size,          // From order_product pivot table
                    'product_details' => $product->productDetails->map(function ($detail) use ($product) {
                        return [
                            'id' => $detail->id,
                            'product_id' => $product->id,
                            'price' => $detail->price,
                            'image' => $detail->image,
                            'color' => $detail->color,
                            'size' => $detail->size,
                            'stock' => $detail->stock,
                            'typeprice' => $detail->typeprice,
                            'typeimage' => $detail->typeimage,
                            'typename' => $detail->typename,
                            'typestock' => $detail->typestock,
                            'created_at' => $detail->created_at,
                            'updated_at' => $detail->updated_at,
                        ];
                    })
                ];
            })
        ];
    }
}
