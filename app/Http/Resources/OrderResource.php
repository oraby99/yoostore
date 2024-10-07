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
        ];
    }
    
    
}
