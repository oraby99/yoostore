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
        if (!$userId) {
            return ApiResponse::send(false, 'User ID is required', [], 400);
        }
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
        if (!$userId) {
            return ApiResponse::send(false, 'User ID is required', [], 400);
        }
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
        if (!$userId) {
            return ApiResponse::send(false, 'User ID is required', [], 400);
        }
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
        if ($userId && !User::where('id', $userId)->exists()) {
            return ApiResponse::send(false, 'User not found', null);
        }
        $product = ImportedProduct::find($productId);
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
            $productResource = new ProductResource($product, $userId);
            return ApiResponse::send(true, 'Product retrieved successfully', $productResource);
        } else {
            return ApiResponse::send(false, 'Product not found', null);
        }
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
    public function getOffersByTag(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $userId = request()->header('user_id');
        if (!$userId) {
            return ApiResponse::send(false, 'User ID is required', [], 400);
        }
    
        $offer = Offer::first();
        if ($offer) {
            $offerTag = $offer->getTranslation('tag', 'en');
    
            // Fetch parent products with the offer tag and eager load rates
            $parentProducts = ImportedProduct::where('tags', $offerTag)
                ->whereNull('parent') // Ensure only parent products are fetched
                ->with(['rates', 'childproduct.rates']) // Eager load rates for parent and child products
                ->paginate($perPage);
    
            // Fetch child products for each parent product
            $productsWithChildren = $parentProducts->map(function ($parentProduct) use ($userId) {
                $childProducts = ImportedProduct::where('parent', $parentProduct->sku)
                    ->with('rates') // Eager load rates for child products
                    ->get();
    
                // Format parent and child products using ProductResource
                $formattedParent = new ProductResource($parentProduct, $userId);
                $formattedChildren = $childProducts->map(function ($childProduct) use ($userId) {
                    return new ProductResource($childProduct, $userId);
                });
    
                return [
                    'parent' => $formattedParent,
                    'children' => $formattedChildren,
                ];
            });
    
            return ApiResponse::send(true, 'Offer and related products retrieved successfully', [
                'offer' => new OfferResource($offer),
                'products' => [
                    'data' => $productsWithChildren,
                    'pagination' => [
                        'total' => $parentProducts->total(),
                        'current_page' => $parentProducts->currentPage(),
                        'per_page' => $parentProducts->perPage(),
                        'last_page' => $parentProducts->lastPage(),
                        'next_page_url' => $parentProducts->nextPageUrl(),
                        'prev_page_url' => $parentProducts->previousPageUrl(),
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
        if (!$userId) {
            return ApiResponse::send(false, 'User ID is required', [], 400);
        }
    
        $profile = Profile::first();
        if ($profile) {
            $profileTag = $profile->getTranslation('tag', 'en');
    
            // Fetch parent products with the profile tag and eager load rates
            $parentProducts = ImportedProduct::where('tags', $profileTag)
                ->whereNull('parent') // Ensure only parent products are fetched
                ->with(['rates', 'childproduct.rates']) // Eager load rates for parent and child products
                ->paginate($perPage);
    
            // Fetch child products for each parent product
            $productsWithChildren = $parentProducts->map(function ($parentProduct) use ($userId) {
                $childProducts = ImportedProduct::where('parent', $parentProduct->sku)
                    ->with('rates') // Eager load rates for child products
                    ->get();
    
                // Format parent and child products using ProductResource
                $formattedParent = new ProductResource($parentProduct, $userId);
                $formattedChildren = $childProducts->map(function ($childProduct) use ($userId) {
                    return new ProductResource($childProduct, $userId);
                });
    
                return [
                    'parent' => $formattedParent,
                    'children' => $formattedChildren,
                ];
            });
    
            return ApiResponse::send(true, 'Profile product and related products retrieved successfully', [
                'profile' => new ProfileResource($profile),
                'products' => [
                    'data' => $productsWithChildren,
                    'pagination' => [
                        'total' => $parentProducts->total(),
                        'current_page' => $parentProducts->currentPage(),
                        'per_page' => $parentProducts->perPage(),
                        'last_page' => $parentProducts->lastPage(),
                        'next_page_url' => $parentProducts->nextPageUrl(),
                        'prev_page_url' => $parentProducts->previousPageUrl(),
                    ],
                ],
            ]);
        }
    
        return ApiResponse::send(false, 'No profile found', []);
    }
}

