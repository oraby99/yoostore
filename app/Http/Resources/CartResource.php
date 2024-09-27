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
            'product'         => [
                'id'            => $product->id,
                'name'          => $product->name,
                'description'   => $product->description,
                'longdescription' => $product->longdescription,
                'tag'           => $product->tag,
                'discount'      => $product->discount,
                'attributes'    => $product->attributes,
                'deliverytime'  => $product->deliverytime,
                'category_id'   => $product->category_id,
                'sub_category_id' => $product->sub_category_id,
                'created_at'    => $product->created_at,
                'updated_at'    => $product->updated_at,
            ],
            'product_detail'  => [
                'id'            => $productDetail->id,
                'product_id'    => $productDetail->product_id,
                'price'         => $productDetail->price,
                'image'         => url('storage/' . $productDetail->image),
                'color'         => $productDetail->color,
                'size'          => $productDetail->size,
                'stock'         => $productDetail->stock,
                'typeprice'     => $productDetail->typeprice,
                'typeimage'     => $productDetail->typeimage ? url('storage/' . $productDetail->typeimage) : null,
                'typename'      => $productDetail->typename,
                'typestock'     => $productDetail->typestock,
                'created_at'    => $productDetail->created_at,
                'updated_at'    => $productDetail->updated_at,
            ],
            'size'            => $this->size,
            'quantity'        => $this->quantity,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
            'default_address' => $defaultAddress ? new AddressResource($defaultAddress) : null,
        ];
    }
}

