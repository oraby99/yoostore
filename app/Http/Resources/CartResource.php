<?php

namespace App\Http\Resources;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray($request)
    {
        $defaultAddress = Address::where('user_id', auth()->id())
                                ->where('is_default', 1)
                                ->first();

        return [
            'id'              => $this->id,
            'product_id'      => $this->product_id,
            'product'         => new ProductResource($this->product),
            'product_detail'  => $this->productDetail ? new ProductDetailResource($this->productDetail) : null,
            'size'            => $this->size,
            'quantity'        => $this->quantity,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
            'default_address' => $defaultAddress ? new AddressResource($defaultAddress) : null,
        ];
    }
}
