<?php

namespace App\Http\Controllers\Api\Cart;


use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id'        => 'required|exists:products,id',
            'product_detail_id' => 'nullable|exists:product_details,id',
            'size'              => 'string|nullable',
        ]);
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->where('product_detail_id', $request->product_detail_id)
            ->where('size', $request->size)
            ->first();
        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            $cartItem = Cart::create([
                'user_id'           => Auth::id(),
                'product_id'        => $request->product_id,
                'product_detail_id' => $request->product_detail_id,
                'size'              => $request->size,
                'quantity'          => 1,
            ]);
        }
        return response()->json([
            'status'  => true,
            'message' => 'Product added to cart',
            'data'    => new CartResource($cartItem),
        ]);
    }
    public function deleteFromCart($cartId)
    {
        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($cartId);
        $cartItem->delete();
        return response()->json([
            'status'  => true,
            'message' => 'Product removed from cart',
            'data'    => new CartResource($cartItem),
        ]);
    }
    public function getUserCarts()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with(['product', 'productDetail'])->get();
        return response()->json([
            'status'  => true,
            'message' => 'All Product cart',
            'data'    => CartResource::collection($cartItems),
        ]);
    }
    public function incrementCart($cartId)
    {
        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($cartId);
        $cartItem->increment('quantity');
        return response()->json([
            'status'  => true,
            'message' => 'All Product cart',
            'data'    => new CartResource($cartItem),
        ]);
    }
    public function decrementCart($cartId)
    {
        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($cartId);
        if ($cartItem->quantity > 1) {
            $cartItem->decrement('quantity');
        } else {
            $cartItem->delete();
        }
        return response()->json([
            'status'  => true,
            'message' => 'Product quantity decreased',
            'data'    => new CartResource($cartItem),
        ]);
    }
}
