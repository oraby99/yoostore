<?php

namespace App\Http\Resources;

use App\Models\Favorite;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        $productDetails = ProductDetailResource::collection($this->productDetails);
        $typeDetails = TypeDetailResource::collection($this->typeDetails);
        $productPrices = $productDetails->map(function($detail) {
            return $detail->price;
        })->filter();
        $typePrices = $typeDetails->map(function($detail) {
            return $detail->typeprice;
        })->filter();
        $allPrices = $productPrices->merge($typePrices);
        $minPrice = $allPrices->min();
        $averageRate = $this->rates()->avg('rate');
        $formattedAverageRate = $averageRate ? number_format($averageRate) : '0';
        $isFav = null;
        if (auth()->check()) {
            $favorite = Favorite::where('product_id', $this->id)
                                ->where('user_id', auth()->id())
                                ->first();
            if ($favorite) {
                $isFav = 1;
            } else {
                $isFav = 0;
            }
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
            'average_rate' => $formattedAverageRate,
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
