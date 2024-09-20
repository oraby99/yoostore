<?php
namespace App\Http\Resources;

use App\Models\Favorite;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        $productDetails = ProductDetailResource::collection($this->productDetails);
        $typeDetails = TypeDetailResource::collection($this->typeDetails); // Use the typeDetails relationship
    
        $productPrices = $productDetails->map(function($detail) {
            return $detail->price;
        })->filter();
    
        $typePrices = $typeDetails->map(function($detail) {
            return $detail->typeprice; // Assuming you have a typeprice field
        })->filter();
    
        $allPrices = $productPrices->merge($typePrices);
        $minPrice = $allPrices->min();
    
        $isFav = null;
        $favs = Favorite::where('product_id', $this->id)->first();
        if ($favs) {
            $isFav = $favs->is_favorite;
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
            'type_details' => $typeDetails, // Ensure this is correctly referencing the relationship
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
