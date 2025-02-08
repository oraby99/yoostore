<?php

namespace App\Http\Controllers\Api\General;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\ImportedProduct;
use App\Models\ProductHistory;
use Illuminate\Http\Request;

class ProductHistoryController extends Controller
{
    public function getUserHistory()
    {
        $userId = auth()->id();
        $productHistories = ProductHistory::where('user_id', $userId)
                                           ->with('product.childproduct')
                                           ->latest()
                                           ->get();

        $parentProducts = [];
        foreach ($productHistories as $history) {
            $product = $history->product;
            if ($product) {
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
        }
        $products = array_values($parentProducts);
        return ApiResponse::send(true, 'User product history retrieved successfully', $products);
    }
}

