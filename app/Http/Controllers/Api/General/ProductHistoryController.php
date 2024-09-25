<?php

namespace App\Http\Controllers\Api\General;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\ProductHistory;
use Illuminate\Http\Request;

class ProductHistoryController extends Controller
{
    public function getUserHistory()
    {
        $userId = auth()->id();
        $productHistories = ProductHistory::where('user_id', $userId)
                                           ->with('product')
                                           ->latest()
                                           ->get();
        $products = $productHistories->map(function ($history) {
            return new ProductResource($history->product);
        });
        return ApiResponse::send(true, 'User product history retrieved successfully', $products);
    }
}
