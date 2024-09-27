<?php

namespace App\Http\Resources;

use App\Models\Favorite;
use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Facades\Http;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        $currency = $request->header('currency') ?? 'KWD';
        $exchangeRate = $this->getExchangeRate($currency);
        
        // Retrieve the product_detail_id from the request
        $productDetailId = $request->product_detail_id;

        // Filter product details to return only the one with the matching product_detail_id
        $filteredProductDetails = $this->productDetails->filter(function ($detail) use ($productDetailId) {
            return $detail->id == $productDetailId;
        });

        // Map the filtered product detail to return only one
        $productDetails = ProductDetailResource::collection($filteredProductDetails)->map(function ($detail) use ($exchangeRate) {
            $detail->price = round($detail->price * $exchangeRate, 2);
            return $detail;
        });

        $typeDetails = TypeDetailResource::collection($this->typeDetails)->map(function ($detail) use ($exchangeRate) {
            $detail->typeprice = round($detail->typeprice * $exchangeRate, 2);
            return $detail;
        });

        $productPrices = $productDetails->pluck('price')->filter();
        $typePrices = $typeDetails->pluck('typeprice')->filter();
        $allPrices = $productPrices->merge($typePrices);
        $minPrice = $allPrices->isNotEmpty() ? round($allPrices->min() * $exchangeRate, 2) : null;

        $averageRate = $this->rates()->avg('rate');
        $formattedAverageRate = $averageRate ? number_format($averageRate, 2) : '0';

        $isFav = null;
        if (auth()->check()) {
            $favorite = Favorite::where('product_id', $this->id)
                                ->where('user_id', auth()->id())
                                ->first();
            $isFav = $favorite ? 1 : 0;
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
            'product_details' => $productDetails->isNotEmpty() ? $productDetails->first() : null, // Return only the first matching detail
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
    private function getExchangeRate($currency)
    {
        if ($currency == 'KWD') {
            return 1;
        }
        $response = Http::get('https://apilayer.net/api/live', [
            'access_key' => '37469631e33a35df1d8349fd069149e6',
            'currencies' => $currency,
            'source' => 'KWD',
            'format' => 1,
        ]);
        if ($response->successful()) {
            $exchangeRates = $response->json();
            return $exchangeRates['quotes']['KWD' . $currency] ?? 1;
        }
        return 1;
    }
}



