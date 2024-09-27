<?php


namespace App\Http\Resources;

use App\Models\Address;
use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray($request)
    {
        $defaultAddress = Address::where('user_id', auth()->id())
                                ->where('is_default', 1)
                                ->first();
        $productDetail = ProductDetail::where('id', $this->product_detail_id)->first();
        $product = Product::where('id', $this->product_id)->first();
        return [
            'id'              => $this->id,
            'product_id'      => $this->product_id,
            'product'         => $product,
            'product_detail'  => $productDetail ,
            'size'            => $this->size,
            'quantity'        => $this->quantity,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
            'default_address' => $defaultAddress ? new AddressResource($defaultAddress) : null,
        ];
    }
}

