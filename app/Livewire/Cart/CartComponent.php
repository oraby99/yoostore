<?php

namespace App\Livewire\Cart;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CartComponent extends Component
{
    public $products; 
    public $total = 0; 
    public $discount = 0; 
    public $quantities = [];

    public function mount()
    {
        $this->products = Cart::where('user_id', Auth::user()->id)->get();

        foreach ($this->products as $product) {
            $this->quantities[$product->id] = $product->quantity; 
        }
    }

    public function incrementQuantity($productId)
    {
        $this->quantities[$productId]++;
        $item = Cart::find($productId);
        $item->update(['quantity' => $this->quantities[$productId]]);
    }

    public function decrementQuantity($productId)
    {
        if ($this->quantities[$productId] > 1) {
            $this->quantities[$productId]--;
            $item = Cart::find($productId);
            $item->update(['quantity' => $this->quantities[$productId]]);
        } else {
            $this->removeProduct($productId);
        }
    }

    public function removeProduct($productId)
    {
        $this->products = $this->products->filter(function ($product) use ($productId) {
            return $product->id != $productId;
        });

        Cart::where('id', $productId)->delete();
        unset($this->quantities[$productId]);
    }

    private function calculateTotal()
    {
        $this->total = 0;
        foreach ($this->products as $product) {
            $this->total += $product->productDetail->typeprice * $this->quantities[$product->id];
        }
        return $this->total;
    }
    public function calculateTotalWithDiscount()
    {
        $this->discount = 0;
    
        foreach ($this->products as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $this->discount += $product->discount;
            }
        }
    }


    public function checkout()

    {
        
        return redirect()->route('checkout');
    }
    
    public function render()
    {
        // Refresh the products to keep them in sync
        $this->products = Cart::where('user_id', Auth::user()->id)->get();

        // dd($this->products);
        return view('livewire.cart.cart-component', [
            'total' => $this->calculateTotal()
        ]);
    }
}