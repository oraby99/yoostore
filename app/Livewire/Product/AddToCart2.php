<?php

namespace App\Livewire\Product;

use App\Livewire\Rating\Rating;
use App\Models\Cart;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\ProductHistory;
use App\Models\Rate;
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
    public $rating;

    public function mount(Request $request)
    {

        $userId = Auth::id();
        $this->rating = Rate::where('product_id', $request->id)->avg('rate');

        $this->productID = $request->id; 
        $this->product = Product::where('id', $this->productID)->first();
        if ($this->product->productDetails->isNotEmpty()) {
            $this->selectedVariationId = $this->product->productDetails->first()->id;
            $this->selectedPrice = $this->product->productDetails->first()->price;
        }


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


    public function addToWishlist(){
        $userId = Auth::id();
        if ($userId) {
            $wishlist = Favorite::where([
                'user_id' => $userId,
                'product_id' => $this->productID
            ])->first();
            session()->flash('success', 'you already have this item in your wishlist');
            if (!$wishlist) {
                Favorite::create([
                    'user_id' => $userId,
                    'product_id' => $this->productID,
                    'is_favorite' => 1,
                ]);
                session()->flash('success', 'Item added to wishlist successfully!');
            }
        }
        else{
            return redirect()->route('login');
        }
    }

    public function copied()
    {
        session()->flash('success', 'URL copied to clipboard');

    }
    public function render()
    {
        return view('livewire.product.add-to-cart2');
    }
}
