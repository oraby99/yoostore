<?php

namespace App\Http\Controllers\Api\General;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Product;

class FavoriteController extends Controller
{
    public function addFavorite(Request $request)
    {
        $user = auth()->user();
        $productId = $request->input('product_id');
        $product = Product::find($productId);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $favorite = Favorite::where('user_id', $user->id)
                             ->where('product_id', $productId)
                             ->first();
    
        if ($favorite) {
            return response()->json(['message' => 'Product is already in favorites']);
        }
        Favorite::create([
            'user_id' => $user->id,
            'product_id' => $productId,
            'is_favorite' => 1,
        ]);
        return response()->json(['message' => 'Product added to favorites'], 200);
    }
    public function removeFavorite($productId)
    {
        $user = auth()->user();
        $favorite = Favorite::where('user_id', $user->id)
                             ->where('product_id', $productId)
                             ->first();
        
        if (!$favorite) {
            return response()->json(['error' => 'Favorite not found'], 404);
        }
        $favorite->delete();
        return response()->json(['message' => 'Product removed from favorites'], 200);
    }
    public function getFavorites()
    {
        $user = auth()->user();
        $favorites = Favorite::where('user_id', $user->id)
            ->with(['product.productDetails', 'product.typeDetails'])
            ->get();
        $favoriteProducts = $favorites->map(function ($favorite) {
            return new ProductResource($favorite->product);
        });
        return ApiResponse::send(true, 'Favorites retrieved successfully', $favoriteProducts);
    }
    
}

