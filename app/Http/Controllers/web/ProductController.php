<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index($id)
    {

        $product = Product::where('id', $id)->first();

        $products = Product::where('category_id', $product->category_id)->where('id', '!=', $id)->get();
        // dd($products);
        return view('yoostore.product' , [
            'product' => $product,
            'products' => $products
        ]);
    }
}
