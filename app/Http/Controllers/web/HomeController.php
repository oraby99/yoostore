<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    

    public function index()
    {
        $products =Product::where('is_published', 1)->orderby('id','desc')->with(['productDetails' , 'images'])->get();
        $banner1 = Banner::where('name->en', 'Section One')->first();
        $banner2 = Banner::where('name->en', 'Section Two')->first();
        $banner3 = Banner::where('name->en', 'Section Three')->first();
        $banner4 = Banner::where('name->en', 'Section Four')->first();
        $categories = Category::with('subcategories')->get();

        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $squared = $collection->map(function ($item) {
            return $item = $item * $item;
        });
        
        
        // $user = auth()->user();
        // dd( $banner1);
        return view('yoostore.home' , [
            'products' => $products,
            'categories' => $categories,
            'banner1' => $banner1,
            'banner2' => $banner2,
            'banner3' => $banner3,
            'banner4' => $banner4
        ]);
    }


  
}
