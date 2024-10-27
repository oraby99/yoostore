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
        $this->refreshCart();
    }

    public function refreshCart()
    {
        $this->products = Cart::where('user_id', Auth::user()->id)->get();

        foreach ($this->products as $product) {
            $this->quantities[$product->id] = $product->quantity;
        }
        
        $this->calculateTotal();
        $this->calculateTotalWithDiscount();
    }

    public function incrementQuantity($productId)
    {
        $this->quantities[$productId]++;
        $this->updateCart($productId);
    }

    public function decrementQuantity($productId)
    {
        if ($this->quantities[$productId] > 1) {
            $this->quantities[$productId]--;
            $this->updateCart($productId);
        } else {
            $this->removeProduct($productId);
        }
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);

        if ($product) {
            Cart::updateOrCreate(
                ['user_id' => Auth::user()->id, 'product_id' => $productId],
                ['quantity' => $this->quantities[$productId] ?? 1]
            );
            session()->flash('message', 'Product added to cart!');
            $this->refreshCart(); 
        }
    }

    public function removeProduct($productId)
    {
        // dd($productId);
        Cart::where('id', $productId)->delete();
        unset($this->quantities[$productId]);
        $this->refreshCart(); 
    }

    private function updateCart($productId)
    {
        $item = Cart::find($productId);
        
        if ($item) {
            $item->update(['quantity' => $this->quantities[$productId]]);
            $this->calculateTotal(); 
        }
    }

    public function gotoshop()

    {
        return redirect()->route('index');
    }
    private function calculateTotal()
    {
        $this->total = 0;
    
        foreach ($this->products as $product) {
            $quantity = $this->quantities[$product->id] ?? 1; 
            
            $price = 0;
    
            if (!empty($product->productDetail->typeprice)) {
                $price += $product->productDetail->typeprice;
            }
            
            if (!empty($product->productDetail->price)) {
                $price += $product->productDetail->price;
            }
    
            $this->total += $price * $quantity;
        }
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
        $this->refreshCart(); // Refresh the products to keep them in sync

        return view('livewire.cart.cart-component', [
            'total' => $this->total,
            'products' => $this->products,
            'quantities' => $this->quantities,
            'discount' => $this->discount
        ]);
    }
}
