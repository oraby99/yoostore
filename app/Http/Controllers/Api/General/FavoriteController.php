<?php

namespace App\Http\Controllers\Api\General;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\ImportedProduct;
use App\Models\Product;

class FavoriteController extends Controller
{
    public function addFavorite(Request $request)
    {
        $user = auth()->user();
        $productId = $request->input('product_id');
        $product = ImportedProduct::find($productId);
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
            'user_id'     => $user->id,
            'product_id'  => $productId,
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
    public function getFavorites(Request $request)
    {
        $userId = request()->header('user_id');
        if (!$userId) {
            return ApiResponse::send(false, 'User ID is required', [], 400);
        }
        $favorites = Favorite::with('product.childproduct')
                             ->where('user_id', $userId)
                             ->get();
    
        $parentProducts = [];
        foreach ($favorites as $favorite) {
            $product = $favorite->product;
            if ($product->parent) {
                $parentProduct = ImportedProduct::where('sku', $product->parent)->first();
                if ($parentProduct && !isset($parentProducts[$parentProduct->id])) {
                    $parentProducts[$parentProduct->id] = new ProductResource($parentProduct, $userId);
                }
            } else {
                if (!isset($parentProducts[$product->id])) {
                    $parentProducts[$product->id] = new ProductResource($product, $userId);
                }
            }
        }
        $favoriteProducts = array_values($parentProducts);
        return ApiResponse::send(true, 'Favorites retrieved successfully', $favoriteProducts);
    }

}

