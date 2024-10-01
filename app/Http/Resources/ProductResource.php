<?php

namespace App\Http\Resources;

use App\Models\Favorite;
use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Facades\Http;

class ProductResource extends JsonResource
{
    protected $userId;
    public function __construct($resource, $userId = null)
    {
        parent::__construct($resource);
        $this->userId = $userId;
    }
    public function toArray($request)
    {
        $currency = $request->header('currency') ?? 'KWD';
        $exchangeRate = $this->getExchangeRate($currency);
        $productDetailId = $request->product_detail_id;
        $filteredProductDetails = $productDetailId ? $this->productDetails->filter(function ($detail) use ($productDetailId) {
            return $detail->id == $productDetailId;
        }) : $this->productDetails;
    
        // Apply exchange rates to product details and type details
        $productDetails = ProductDetailResource::collection($filteredProductDetails)->map(function ($detail) use ($exchangeRate) {
            if ($detail->price) {
                $detail->price = round($detail->price * $exchangeRate, 2);
            }
            return $detail;
        });
        
        $typeDetails = TypeDetailResource::collection($this->typeDetails)->map(function ($detail) use ($exchangeRate) {
            if ($detail->typeprice) {
                $detail->typeprice = round($detail->typeprice * $exchangeRate, 2);
            }
            return $detail;
        });
    
        $productPrices = $productDetails->pluck('price')->filter(function ($price) {
            return !is_null($price) && $price > 0;
        });
    
        $typePrices = $typeDetails->pluck('typeprice')->filter(function ($price) {
            return !is_null($price) && $price > 0;
        });
    
        $allPrices = $productPrices->merge($typePrices);
        $minPrice = $allPrices->isNotEmpty() ? round($allPrices->min(), 2) : null;
        $averageRate = $this->rates()->avg('rate');
        $formattedAverageRate = $averageRate ? number_format($averageRate, 2) : '0';
    
        $isFav = null;
        if ($this->userId) {
            $favorite = Favorite::where('product_id', $this->id)
                                ->where('user_id', $this->userId)
                                ->first();
            $isFav = $favorite ? 1 : 0;
        }
    
        // Handle product or type details for the response
        $details = $this->productDetails->isNotEmpty() ? $this->productDetails->first() : ($this->typeDetails->isNotEmpty() ? $this->typeDetails->first() : null);
        if ($details) {
            if (isset($details->typeimage)) {
                $details->typeimage = url('storage/' . $details->typeimage);
            }
            if (isset($details->image)) {
                $details->image = url('storage/' . $details->image);
            }
        }
    
        return [
            'id' => $this->id,
            'name' => $this->getTranslations('name'),
            'description' => $this->getTranslations('description'),
            'longdescription' => $this->getTranslations('longdescription'),
            'tag' => $this->getTranslations('tag'),
            'discount' => $this->discount,
            'attributes' => $this->attributes, // Use plain attributes since they are not translatable
            'deliverytime' => $this->deliverytime,
            'category_id' => $this->category_id,
            'sub_category_id' => $this->sub_category_id,
            'min_price' => $minPrice,
            'average_rate' => $formattedAverageRate,
            'is_fav' => $isFav,
            'details' => $details ? $details->toArray() : null,
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



