<?php

namespace App\Http\Controllers\Api\Banners;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Product;
use Illuminate\Http\Request;
class BannerProductController extends Controller
{
    public function getBannersWithProducts()
    {
        $banners = Banner::all();
        $response = [];
        foreach ($banners as $index => $banner) {
            $bannerTag = $banner->getTranslation('tag', 'en'); 
            $products = Product::where('tag->en', $bannerTag)->with('productDetails')
                                ->take($index == 3 ? null : 10)
                                ->get();

            $response[] = [
                'banner' => $banner,
                'products' => $products
            ];
        }
        return response()->json($response);
    }
}

