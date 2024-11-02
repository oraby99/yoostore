<?php

namespace App\Livewire\Product;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AddToCart2 extends Component
{



    public $productID;
    public $product;
    public $mainStock;
    public $selectedPrice; 
    public $selectedVariationId; 
    public $quantity = 1;

    public function mount(Request $request)
    {
        $this->productID = $request->id; 
        $this->product = Product::where('id', $this->productID)->first();
        if ($this->product->productDetails->isNotEmpty()) {
            $this->selectedVariationId = $this->product->productDetails->first()->id;
            $this->selectedPrice = $this->product->productDetails->first()->price;
        }


        $userId = Auth::id();
        if ($userId) {
            $history = ProductHistory::where([
                'user_id' => $userId,
                'product_id' => $this->productID
            ])->first();
            if (!$history) {
                ProductHistory::create([
                    'user_id' => $userId,
                    'product_id' => $this->productID,
                ]);
            }
            
        }
        //refresh page to load selected price when selecting variatio
    }

    public function updatedSelectedVariationId($value)
    {
        $this->selectVariation($value);
    }

    public function selectVariation($typeId)
    {
        $variation = $this->product->productDetails->where('id', $typeId)->first();
        
        if ($variation) {
            $this->selectedVariationId = $typeId;
            $this->selectedPrice = $variation->price;
        }

    }

    public function incrementQuantity()
    {
        $this->quantity++; 
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--; 
        }
    }

    public function addToCart()
    {

        // dd($this->selectedPrice);
        $userId = Auth::id();

        if (!$userId) {
            return redirect()->route('login');
        }else{
            
            Cart::create([
                'user_id' => $userId,
                'product_id' => $this->productID,
                'product_detail_id' => $this->selectedVariationId,
                'quantity' => $this->quantity,
                'size' => null, 
            ]);
        }

        $this->quantity = 1; 
        session()->flash('success', 'Item added to cart successfully!');
    }

    public function render()
    {
        return view('livewire.product.add-to-cart2');
    }
}
