<?php

namespace App\Livewire\Product;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AddToCart extends Component
{
    public $productID;
    public $product;
    public $mainStock;
    public $selectedPrice; 
    public $selectedType;
    public $selectedVariationId; 
    public $quantity = 1;
    public function mount(Request $request)
    {
        $this->productID = $request->id; 
        $this->product = Product::where('id', $this->productID)->first();
        if ($this->product->productDetails->isNotEmpty()) {
            $this->selectedVariationId = $this->product->productDetails->first()->id;
            $this->selectedPrice = $this->product->productDetails->first()->type_price;
        }
    }

    public function selectVariation($typeId)
    {
        $variation = $this->product->productDetails->where('id', $typeId)->first();
        if ($variation) {
            $this->selectedVariationId = $typeId;
            $this->selectedPrice = $variation->type_price;
        }
        // dd($this->selectedPrice);

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
        $userId = Auth::id();
        Cart::create([
            'user_id' =>  $userId ,
            'product_id' => $this->productID,
            'product_detail_id' => $this->selectedVariationId,
            'quantity' => $this->quantity,
            'size' => 's', 
        ]);

        // dd($userId);
        $this->quantity = 1;
        session()->flash('success', 'Item added to cart successfully!');
    }


    public function render()
    {
        $this->product = Product::where('id', $this->productID)->first();
        $variations = $this->product->productDetails;

        $this->mainStock = $variations->sum('typestock');
        
        //  dd($this->selectedPrice);
        return view('livewire.product.add-to-cart' , 
    );
    }
}
