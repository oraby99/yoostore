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
        $products =Product::orderby('id','desc')->with(['productDetails' , 'images'])->get();
        $banner4 = Banner::where('name->en', 'Section Two')->first();
        $categories = Category::with('subcategories')->get();

        
        // $user = auth()->user();
        // dd( $user);
        return view('yoostore.home' , [
            'products' => $products,
            'categories' => $categories,
            'banner4' => $banner4
        ]);
    }


  
}
