<?php

namespace App\Http\Controllers\Api\Banners;

use App\Http\Controllers\Controller;
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
            $response[] = [
                'banner' => $banner,
                'products' => $products
            ];
        }
        return response()->json($response);
    }
    public function categories()
    {
        $categories = Category::with('subcategories')->get();
        return response()->json($categories);

    }
    public function subcategory($id)
    {
        $categories = sub_category::where('category_id',$id)->get();
        return response()->json($categories);
        
    }
    public function products(Request $request)
    {
        $searchKey = $request->query('search');     // Search for name or description
        $page = $request->query('page', 1);         // Pagination page, default is 1
        $perPage = $request->query('per_page', 10); // Items per page, default is 10
        $tag = $request->query('tag');              // Tag to filter by
        $categoryId = $request->query('cat_id');    // Category ID to filter by
        $subCategoryId = $request->query('sub_id'); // Subcategory ID to filter by
        $query = Product::with('productDetails');
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
        return response()->json($products);
    }
    
}

