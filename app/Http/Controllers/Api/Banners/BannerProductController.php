<?php

namespace App\Http\Controllers\Api\Banners;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\OfferResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\SubCategoryResource;
use App\Models\Banner;
use App\Models\Category;
use App\Models\ImportedProduct;
use App\Models\Offer;
use App\Models\Product;
use App\Models\ProductHistory;
use App\Models\Profile;
use App\Models\sub_category;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
class BannerProductController extends Controller
{
    public function getBannersWithProducts()
    {
        $banners = Banner::all();
        $response = [];
        foreach ($banners as $index => $banner) {
            $bannerTag = $banner->getTranslation('tag', 'en');
            $query = ImportedProduct::where('tags', $bannerTag)
                ->whereNull('parent')
                ->with('childproduct');
            if ($index < 3) {
                $query->take(10);
            }
            $parentProducts = $query->get();
            $banner->products = ProductResource::collection($parentProducts);
            $response[] = new BannerResource($banner);
        }
        return ApiResponse::send(true, 'Banners with products retrieved successfully', $response);
    }
    public function getFourthBannerProducts(Request $request)
    {
        $banner = Banner::skip(3)->first();
        if ($banner) {
            $bannerTag = $banner->getTranslation('tag', 'en');
            $query = ImportedProduct::where('tags', $bannerTag)
                ->whereNull('parent')
                ->with('childproduct');
            $perPage = $request->query('per_page', 10);
            $products = $query->paginate($perPage);
            $banner->products = $products;
            return ApiResponse::send(true, 'Products related to the fourth banner retrieved successfully', [
                new BannerResource($banner)
            ]);
        }
        return ApiResponse::send(false, 'Fourth banner not found', []);
    }
    public function categories()
    {
        $categories = Category::with('subcategories')->get();
        return CategoryResource::collection($categories);
    }
    public function subcategory($id)
    {
        $subcategories = sub_category::where('category_id', $id)->get();
        return SubCategoryResource::collection($subcategories);
    }
    public function products(Request $request)
    {   
        $searchKey = $request->query('search'); 
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);
        $tag = $request->query('tag');
        $categoryId = $request->query('cat_id');
        $subCategoryId = $request->query('sub_id');
        $currency = $request->header('currency', 'KWD');
        $query = Product::with('productDetails')->with('images');
        if ($searchKey) {
            $query->where(function ($q) use ($searchKey) {
                $q->where('name->en', 'like', '%' . $searchKey . '%')
                  ->orWhere('description->en', 'like', '%' . $searchKey . '%');
            });
        }
        if ($tag) {
            $query->where('tag->en', $tag);
        }
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        if ($subCategoryId) {
            $query->where('sub_category_id', $subCategoryId);
        }
        $products = $query->paginate($perPage, ['*'], 'page', $page);
        return ProductResource::collection($products)->additional(['currency' => $currency]);
    }    
    public function getOffers()
    {
        $offers = Offer::all();
        return ApiResponse::send(true, 'Offers retrieved successfully', OfferResource::collection($offers));
    }
    public function getOffersByTag(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $offer = Offer::first();
        if ($offer) {
            $offerTag = $offer->getTranslation('tag', 'en');
            $products = Product::where('tag->en', $offerTag)
                ->with('productDetails')
                ->paginate($perPage);
            return ApiResponse::send(true, 'Offer and related products retrieved successfully', [
                'offer' => new OfferResource($offer),
                'products' => [
                    'data' => ProductResource::collection($products),
                    'pagination' => [
                        'total' => $products->total(),
                        'current_page' => $products->currentPage(),
                        'per_page' => $products->perPage(),
                        'last_page' => $products->lastPage(),
                        'next_page_url' => $products->nextPageUrl(),
                        'prev_page_url' => $products->previousPageUrl(),
                    ]
                ]
            ]);
        }
        return ApiResponse::send(false, 'No offer found', []);
    }
    public function getProfileByTag(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $offer = Profile::first();
        if ($offer) {
            $offerTag = $offer->getTranslation('tag', 'en');
            $products = Product::where('tag->en', $offerTag)
                ->with('productDetails')
                ->paginate($perPage);
            return ApiResponse::send(true, 'Profile product and related products retrieved successfully', [
                'offer' => new ProfileResource($offer),
                'products' => [
                    'data' => ProductResource::collection($products),
                    'pagination' => [
                        'total' => $products->total(),
                        'current_page' => $products->currentPage(),
                        'per_page' => $products->perPage(),
                        'last_page' => $products->lastPage(),
                        'next_page_url' => $products->nextPageUrl(),
                        'prev_page_url' => $products->previousPageUrl(),
                    ]
                ]
            ]);
        }
        return ApiResponse::send(false, 'No offer found', []);
    }
    public function productById($productId)
    {
        $userId = request()->header('user_id'); // Retrieve the user_id from the request header
        if ($userId && !User::where('id', $userId)->exists()) {
            return ApiResponse::send(false, 'User not found', null);
        }
    
        $product = Product::where('id', $productId)
                          ->with('productDetails', 'images')
                          ->first();
        
        if ($product) {
            if ($userId) {
                $existingHistory = ProductHistory::where('user_id', $userId)
                                                 ->where('product_id', $productId)
                                                 ->first();
                if (!$existingHistory) {
                    ProductHistory::create([
                        'user_id' => $userId,
                        'product_id' => $productId,
                    ]);
                }
            }
            
            // Pass the user_id (which could be null) to the resource
            $productResource = new ProductResource($product, $userId);
            return ApiResponse::send(true, 'Product retrieved successfully', $productResource);
        } else {
            return ApiResponse::send(false, 'Product not found', null);
        }
    }
    
}

