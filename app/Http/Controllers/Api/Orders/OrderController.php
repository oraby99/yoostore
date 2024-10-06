<?php

namespace App\Http\Controllers\Api\Orders;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderCancellation;
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
        $order->update(['status' => 'Cancelled']);
    
        return response()->json([
            'status'  => true,
            'message' => 'Order cancelled successfully',
        ], 200);
    }
    public function trackOrder($orderId)
    {
        $order = Order::find($orderId);
        if (!$order) {
            return response()->json([
                'status'  => false,
                'message' => 'Order not found',
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data'   => [
                'order_id'    => $order->id,
                'status'      => $order->status,
                'payment_status' => $order->payment_status,
                'payment_method' => $order->payment_method,
                'created_at'  => $order->created_at,
                'total_price' => $order->total_price,
            ],
        ], 200);
    }
    public function getUserOrders()
    {
        $user = auth()->user();
        $Products = Cart::where('user_id', $user->id)->get();
        $orders   = Order::where('user_id',$user->id )->get();
        return response()->json([
            'status'   => true,
            'data'     => $orders,
            'Producrs' => $Products,
        ], 200);
    }
}