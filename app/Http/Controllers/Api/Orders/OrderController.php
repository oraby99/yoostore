<?php

namespace App\Http\Controllers\Api\Orders;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderCancellation;
use App\Models\OrderProduct;
use App\Models\OrderStatusChange;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function cancelOrder(Request $request, $orderId)
    {
        $order = Order::find($orderId);
        if (!$order) {
            return response()->json([
                'status'  => false,
                'message' => 'Order not found',
            ], 404);
        }
        if ($order->status === 'Cancelled') {
            return response()->json([
                'status'  => false,
                'message' => 'Order is already cancelled',
            ], 400);
        }
        if ($order->status === 'Delivered') {
            return response()->json([
                'status'  => false,
                'message' => 'Delivered orders cannot be cancelled',
            ], 400);
        }
        $request->validate([
            'reason'  => 'required|string',
            'comment' => 'nullable|string',
        ]);
        OrderCancellation::create([
            'order_id' => $order->id,
            'reason'   => $request->reason,
            'comment'  => $request->comment,
        ]);
        OrderStatusChange::create([
            'order_id' => $order->id,
            'status'   => 'Cancelled',
        ]);

        $order->update(['status' => 'Cancelled']);
    
        return response()->json([
            'status'  => true,
            'message' => 'Order cancelled successfully',
        ], 200);
    }

    public function trackOrder($orderId)
    {
        // Find the order by ID
        $order = Order::find($orderId);
        
        // Check if the order exists
        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found',
            ], 404);
        }
        
        // Fetch all status changes from the status_changes table
        $statusHistory = $order->statusChanges()
            ->select('status', 'created_at as updated_at')
            ->get()
            ->toArray();
        
        // Always include 'Received' status at the beginning
        array_unshift($statusHistory, [
            'status' => 'Received',
            'updated_at' => $order->created_at,
        ]);
    
        // Return the order tracking information
        return response()->json([
            'status' => true,
            'data' => [
                'order_id' => $order->id,
                'current_status' => $order->status,
                'payment_status' => $order->payment_status,
                'payment_method' => $order->payment_method,
                'total_price' => $order->total_price,
                'status_history' => $statusHistory,
            ],
        ], 200);
    }
    public function getUserOrders()
    {
        $user = auth()->user();
        $orders = Order::where('user_id', $user->id)->get();
        $orderIds = $orders->pluck('id')->toArray();
        $orderProducts = OrderProduct::with(['product', 'productDetail'])
            ->whereIn('order_id', $orderIds)
            ->get();
        $organizedOrders = [];
        foreach ($orders as $order) {
            $orderProductsForCurrentOrder = $orderProducts->where('order_id', $order->id);
            $productsGrouped = [];
            foreach ($orderProductsForCurrentOrder as $orderProduct) {
                $productId = $orderProduct->product_id;
                if (!isset($productsGrouped[$productId])) {
                    $productsGrouped[$productId] = [
                        'id' => $productId,
                        'name' => $orderProduct->product->name,
                        'description' => $orderProduct->product->description,
                        'longdescription' => $orderProduct->product->longdescription,
                        'tag' => $orderProduct->product->tag,
                        'discount' => $orderProduct->product->discount,
                        'attributes' => $orderProduct->product->attributes,
                        'deliverytime' => $orderProduct->product->deliverytime,
                        'category_id' => $orderProduct->product->category_id,
                        'sub_category_id' => $orderProduct->product->sub_category_id,
                        'created_at' => $orderProduct->product->created_at,
                        'updated_at' => $orderProduct->product->updated_at,
                        'product_details' => []
                    ];
                }
                    $productsGrouped[$productId]['product_details'][] = [
                    'id' => $orderProduct->productDetail->id,
                    'price' => $orderProduct->productDetail->price,
                    'image' => $orderProduct->productDetail->image,
                    'image' => $orderProduct->productDetail->image ? url('storage/' . $orderProduct->productDetail->image) : null,
                    'color' => $orderProduct->productDetail->color,
                    'size' => $orderProduct->productDetail->size,
                    'stock' => $orderProduct->productDetail->stock,
                    'typeprice' => $orderProduct->productDetail->typeprice,
                    'typeimage' => $orderProduct->productDetail->typeimage ? url('storage/' . $orderProduct->productDetail->typeimage) : null,
                    'typename' => $orderProduct->productDetail->typename,
                    'typestock' => $orderProduct->productDetail->typestock,
                    'created_at' => $orderProduct->productDetail->created_at,
                    'updated_at' => $orderProduct->productDetail->updated_at,
                ];
            }
    
            $organizedOrders[] = [
                'id' => $order->id,
                'user_id' => $order->user_id,
                'address_id' => $order->address_id,
                'invoice_id' => $order->invoice_id,
                'total_price' => $order->total_price,
                'status' => $order->status,
                'payment_method' => $order->payment_method,
                'payment_status' => $order->payment_status,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
                'products' => array_values($productsGrouped)
            ];
        }
        return response()->json([
            'status' => true,
            'orders' => $organizedOrders,
        ], 200);
    }
    public function getOrderByInvoiceId($invoiceId)
    {
        $order = Order::with(['products', 'productDetails'])
                    ->where('invoice_id', $invoiceId)
                    ->first();
        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found',
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data' => new OrderResource($order),
        ], 200);
    }
}