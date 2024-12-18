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
    
        // Get Product Details
        $productDetailId = $request->product_detail_id;
        $filteredProductDetails = $productDetailId 
            ? $this->productDetails->filter(function ($detail) use ($productDetailId) {
                return $detail->id == $productDetailId;
            }) 
            : $this->productDetails;
    
        $productDetails = ProductDetailResource::collection($filteredProductDetails)->map(function ($detail) use ($exchangeRate) {
            if ($detail->price) {
                $detail->price = round($detail->price * $exchangeRate, 2);
            }
            return $detail;
        });
    
        // Get Type Details
        $typeDetails = TypeDetailResource::collection($this->typeDetails)->map(function ($detail) use ($exchangeRate) {
            if ($detail->typeprice) {
                $detail->typeprice = round($detail->typeprice * $exchangeRate, 2);
            }
            return $detail;
        });
    
        // Filter out null or invalid prices
        $validProductDetails = $productDetails->filter(function ($detail) {
            return $detail->price !== null;
        });
    
        $validTypeDetails = $typeDetails->filter(function ($detail) {
            return $detail->typeprice !== null;
        });
    
        // Combine product and type prices for min price calculation
        $allPrices = $validProductDetails->pluck('price')
                        ->merge($validTypeDetails->pluck('typeprice'))
                        ->filter(function ($price) {
                            return !is_null($price) && $price > 0;
                        });
    
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
            'details' => $validProductDetails->isNotEmpty() ? $validProductDetails : $validTypeDetails,
            'images' => $this->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'product_id' => $image->product_id,
                    'image_path' => $image->image_path,
                    // 'image_path' => url('storage/' . $image->image_path),
                ];
            }),
        ];
    }
    private function getExchangeRate($currency)
    {
        if ($currency == 'KWD') {
            return 1;
        }

        // Fetch exchange rate via API
        $response = Http::get('https://apilayer.net/api/live', [
            'access_key' => 'your_access_key',
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



