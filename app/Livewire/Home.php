<?php

namespace App\Livewire;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Home extends Component
{
    public $products;
    public $search = ''; 

    public function mount()
    {
        $this->products = Product::where('is_published', 1)->with(['productDetails', 'images'])->get();
    }

    public function searchProducts()
    {
        $this->products = Product::where('name', 'like', '%' . $this->search . '%')
        ->where('is_published', 1)
            ->orderBy('id', 'desc')
            ->with(['productDetails', 'images'])
            ->get();
        if($this->products->isEmpty()) {
        $this->products = Product::where('is_published', 1)->orderBy('id', 'desc')->with(['productDetails', 'images'])->get();
            
            session()->flash('error', 'No product found');
        }
    }
   
    public function render()
    {
        $products =Product::orderby('id','desc')->with(['productDetails' , 'images'])->get();
        $banner4 = Banner::where('name->en', 'Section Two')->first();
        $categories = Category::with('subcategories')->get();
        return view('livewire.home' , [
            'products' => $products,
            'categories' => $categories,
            'banner4' => $banner4
        ]);
    }
}
