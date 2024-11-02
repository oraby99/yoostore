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
    

  
    public function addToHistory($id)
    {
        // add to table product_histories recored have fields product_id and user_id and if exist don,t add it
        $userId = Auth::id();

        if ($userId) {
       
            $history = ProductHistory::where([
                'user_id' => $userId,
                'product_id' => $id
            ])->first();
            if (!$history) {
                ProductHistory::create([
                    'user_id' => $userId,
                    'product_id' => $id,
                ]);
            }
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
