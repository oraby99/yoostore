<?php

namespace App\Http\Controllers\Api\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function addFavorite(Request $request)
    {
        $user = auth()->user();
        $productId = $request->input('product_id');
        $favorite = Favorite::where('user_id', $user->id)->where('product_id', $productId)->first();
        if ($favorite) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product is already in favorites.'
            ], 400);
        }
        Favorite::create([
            'user_id'    => $user->id,
            'product_id' => $productId,
            'is_favorite' => 1
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Product added to favorites.'
        ]);
    }
    public function removeFavorite($productId)
    {
        $user = auth()->user();
        $favorite = Favorite::where('user_id', $user->id)->where('product_id', $productId)->first();
        if (!$favorite) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product is not in favorites.'
            ], 400);
        }
        $favorite->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Product removed from favorites.'
        ]);
    }
}

