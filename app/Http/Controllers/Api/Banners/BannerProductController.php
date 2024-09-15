<?php

namespace App\Http\Controllers\Api\Banners;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SubCategoryResource;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\sub_category;
use Illuminate\Http\Request;
class BannerProductController extends Controller
{
    public function getBannersWithProducts()
    {
        $banners = Banner::all();
        $response = [];
        foreach ($banners as $index => $banner) {
            $bannerTag = $banner->getTranslation('tag', 'en');
            if ($index < 3) {
                $products = Product::where('tag->en', $bannerTag)
                    ->with('productDetails')
                    ->take(10)
                    ->get();
            } else {
                $products = [];
            }
            $banner->products = $products;
            $response[] = new BannerResource($banner);
        }
        return ApiResponse::send(true, 'Banners with products retrieved successfully', $response);
    }
    public function getFourthBannerProducts(Request $request)
    {
        $banner = Banner::skip(3)->first();
        if ($banner) {
            $bannerTag = $banner->getTranslation('tag', 'en');
            $perPage = $request->query('per_page', 10);
            $products = Product::where('tag->en', $bannerTag)
                ->with('productDetails')
                ->paginate($perPage);
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
        return ProductResource::collection($products);
        return response()->json($products);
    }
}

