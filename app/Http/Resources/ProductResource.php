<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $productDetails = ProductDetailResource::collection($this->productDetails);
        $productPrices = $productDetails->map(function($detail) {
            return $detail->price;
        })->filter();
        $typeDetails = TypeDetailResource::collection($this->productDetails);
        $typePrices = $typeDetails->map(function($detail) {
            return $detail->typeprice;
        })->filter();
        $allPrices = $productPrices->merge($typePrices);    
        $minPrice = $allPrices->min();
        $user = auth()->user();
        $isFav = null;
        if ($user) {
            $isFav = $this->favorites()->where('user_id', $user->id)->exists() ? 1 : 0;
        }

        return [
            'id' => $this->id,
            'name' => $this->getTranslations('name'),
            'description' => $this->getTranslations('description'),
            'longdescription' => $this->getTranslations('longdescription'),
            'tag' => $this->getTranslations('tag'),
            'discount' => $this->discount,
            'attributes' => $this->attributes,
            'deliverytime' => $this->deliverytime,
            'category_id' => $this->category_id,
            'sub_category_id' => $this->sub_category_id,
            'min_price' => $minPrice,
            'is_fav' => $isFav,
            'product_details' => $productDetails,
            'type_details' => $typeDetails,
            'images' => $this->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'product_id' => $image->product_id,
                    'image_path' => url('storage/' . $image->image_path),
                ];
            }),
        ];
    }
    
    
}
