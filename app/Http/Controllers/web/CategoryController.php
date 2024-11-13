<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index($id)
    {
        $products = Product::where ('sub_category_id', $id)->get();
        return view('yoostore.category' , 
    [
        'products' => $products
    ]);
    }
}
