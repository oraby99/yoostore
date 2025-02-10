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
    public function getBannersWithProducts(Request $request)
    {
        $userId = request()->header('user_id');
        // if (!$userId) {
        //     return ApiResponse::send(false, 'User ID is required', [], 400);
        // }
        $banners = Banner::all();
        $response = [];
        foreach ($banners as $index => $banner) {
            $bannerTag = $banner->getTranslation('tag', 'en');
            $query = ImportedProduct::where('tags', $bannerTag)
                ->whereNull('parent')
                ->with(['childproduct.rates', 'rates']);
            if ($index < 3) {
                $query->take(10);
            }
            $parentProducts = $query->get();
            $products = $parentProducts->map(function ($product) use ($userId) {
                return new ProductResource($product, $userId);
            });
            $banner->products = $products;
            $response[] = new BannerResource($banner, $userId);
        }
        return ApiResponse::send(true, 'Banners with products retrieved successfully', $response);
    }
    public function getFourthBannerProducts(Request $request)
    {
        $userId = request()->header('user_id');
        // if (!$userId) {
        //     return ApiResponse::send(false, 'User ID is required', [], 400);
        // }
        $banner = Banner::skip(3)->first();
        if ($banner) {
            $bannerTag = $banner->getTranslation('tag', 'en');
            $query = ImportedProduct::where('tags', $bannerTag)
            ->whereNull('parent')
            ->with(['childproduct.rates', 'rates']); 
            $perPage = $request->query('per_page', 10);
            $products = $query->paginate($perPage);
            // $banner->products = ProductResource::collection($products)->additional(['user_id' => $userId]);
            $banner->products  = $products->map(function ($product) use ($userId) {
                return new ProductResource($product, $userId);
            });
            return ApiResponse::send(true, 'Products related to the fourth banner retrieved successfully', [
                new BannerResource($banner, $userId)
            ]);
        }
    
        return ApiResponse::send(false, 'Fourth banner not found', []);
    }
    public function products(Request $request)
    {
        $userId = request()->header('user_id');
        // if (!$userId) {
        //     return ApiResponse::send(false, 'User ID is required', [], 400);
        // }
        $searchKey = $request->query('search');
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);
        $tag = $request->query('tag');
        $categoryId = $request->query('cat_id');
        $subCategoryId = $request->query('sub_id');
        $currency = $request->header('currency', 'KWD');
        $query = ImportedProduct::whereNull('parent')->with(['childproduct.rates', 'rates']); 

        if ($searchKey) {
            $query->where(function ($q) use ($searchKey) {
                $q->where('name', 'like', '%' . $searchKey . '%')
                  ->orWhere('description', 'like', '%' . $searchKey . '%');
            });
        }
        if ($tag) {
            $query->where('tags', $tag);
        }
        if ($categoryId) {
            $query->where('categories', $categoryId);
        }
        if ($subCategoryId) {
            $query->where('categories', $subCategoryId);
        }
        $products = $query->paginate($perPage, ['*'], 'page', $page);
        //return ProductResource::collection($products,$userId);
        $productResources = $products->getCollection()->map(function ($product) use ($userId) {
            return new ProductResource($product, $userId);
        });
        return ApiResponse::send(true, 'Products retrieved successfully', $productResources);
    }
    public function productById($productId)
    {
        $userId = request()->header('user_id');
        // if ($userId && !User::where('id', $userId)->exists()) {
        //     return ApiResponse::send(false, 'User not found', null);
        // }
    
        // Fetch the product with its child products and rates
        $product = ImportedProduct::with(['childproduct.rates', 'rates'])->find($productId);
    
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
    
            // Create a ProductResource instance with the product and user ID
            $productResource = new ProductResource($product, $userId);
            return ApiResponse::send(true, 'Product retrieved successfully', $productResource);
        } else {
            return ApiResponse::send(false, 'Product not found', null);
        }
    }
    public function getOffersByTag(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $userId = request()->header('user_id');
    
        $offer = Offer::first();
        if ($offer) {
            $offerTag = $offer->getTranslation('tag', 'en');
    
            // Fetch all products (both parent and child) with the offer tag and eager load rates
            $products = ImportedProduct::where('tags', $offerTag)
                ->with(['rates', 'childproduct.rates']) // Eager load rates for products
                ->paginate($perPage);
    
            // Format products using ProductResource
            $productResources = $products->getCollection()->map(function ($product) use ($userId) {
                return new ProductResource($product, $userId);
            });
    
            return ApiResponse::send(true, 'Offer and related products retrieved successfully', [
                'offer' => new OfferResource($offer),
                'products' => [
                    'data' => $productResources,
                    'pagination' => [
                        'total' => $products->total(),
                        'current_page' => $products->currentPage(),
                        'per_page' => $products->perPage(),
                        'last_page' => $products->lastPage(),
                        'next_page_url' => $products->nextPageUrl(),
                        'prev_page_url' => $products->previousPageUrl(),
                    ],
                ],
            ]);
        }
    
        return ApiResponse::send(false, 'No offer found', []);
    }
    
    public function getProfileByTag(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $userId = request()->header('user_id');
    
        $profile = Profile::first();
        if ($profile) {
            $profileTag = $profile->getTranslation('tag', 'en');
    
            // Fetch all products (both parent and child) with the profile tag and eager load rates
            $products = ImportedProduct::where('tags', $profileTag)
                ->with(['rates', 'childproduct.rates']) // Eager load rates for products
                ->paginate($perPage);
    
            // Format products using ProductResource
            $productResources = $products->getCollection()->map(function ($product) use ($userId) {
                return new ProductResource($product, $userId);
            });
    
            return ApiResponse::send(true, 'Profile product and related products retrieved successfully', [
                'profile' => new ProfileResource($profile),
                'products' => [
                    'data' => $productResources,
                    'pagination' => [
                        'total' => $products->total(),
                        'current_page' => $products->currentPage(),
                        'per_page' => $products->perPage(),
                        'last_page' => $products->lastPage(),
                        'next_page_url' => $products->nextPageUrl(),
                        'prev_page_url' => $products->previousPageUrl(),
                    ],
                ],
            ]);
        }
    
        return ApiResponse::send(false, 'No profile found', []);
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
    public function getOffers()
    {
        $offers = Offer::all();
        return ApiResponse::send(true, 'Offers retrieved successfully', OfferResource::collection($offers));
    }
}

