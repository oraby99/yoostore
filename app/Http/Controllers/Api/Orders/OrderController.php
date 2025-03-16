<?php

namespace App\Http\Controllers\Api\Orders;

use App\Http\Controllers\Api\Payment\FatoorahController;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductResource;
use App\Models\Cart;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderCancellation;
use App\Models\OrderProduct;
use App\Models\OrderStatusChange;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function createNotification($userId, $orderId = null, $message, $type = 'Order')
    {
        Notification::create([
            'user_id'  => $userId,
            'order_id' => $orderId,
            'message'  => $message,
            'type'     => $type,
        ]);
    }
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
        $user = $order->user;
        if ($user && $user->device_token) {
            $data = [
                "registration_ids" => [$user->device_token],
                "notification" => [
                    "title" => 'Order Cancelled',
                    "body" => 'Your order #' . $order->id . ' has been cancelled.',
                ],
                "data" => [
                    "order_id" => (string)$order->id,
                    "type"     => "Cancelled",
                ]
            ];
            $response = FatoorahController::sendFCMNotification($data, 'yoo-store-ed4ba-de6f28257b6d.json');
            $this->createNotification($user->id, $order->id, 'Your order has been cancelled.', 'Cancelled');
            if (isset($response['error']) && !empty($response['error'])) {
                \Log::error('FCM Error: ' . json_encode($response['error']));
                return response()->json(['message' => 'Error: ' . $response['error']], 500);
            }
        }
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

    public function getOrderByInvoiceId($invoiceId)
    {
        $order = Order::with(['orderProducts.product', 'orderProducts.productDetail'])
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
    public function generatePdf(Order $order)
    {
        $order->load(['user', 'address', 'products', 'products.productDetails']); // Load necessary relationships

        $pdf = Pdf::loadView('orders.invoice', compact('order'));

        return $pdf->download('invoice_' . $order->invoice_id . '.pdf');
    }
    protected function formatOrder($order)
    {
        return [
            'id'             => $order->id,
            'user_id'        => $order->user_id,
            'address_id'     => $order->address_id,
            'invoice_id'     => $order->invoice_id,
            'total_price'    => $order->total_price,
            'status'         => $order->orderStatus->name,
            'payment_method' => $order->payment_method,
            'payment_status' => $order->paymentStatus->name,
            'created_at'     => $order->created_at,
            'updated_at'     => $order->updated_at,
            'products'       => $order->orderProducts->map(function ($orderProduct) {
                return [
                    'id'             => $orderProduct->product->id,
                    'name'           => $orderProduct->product->name,
                    'description'    => $orderProduct->product->description,
                    'longdescription'=> $orderProduct->product->longdescription,
                    'tag'            => $orderProduct->product->tag,
                    'discount'       => $orderProduct->product->discount,
                    'attributes'     => $orderProduct->product->attributes,
                    'deliverytime'   => $orderProduct->product->deliverytime,
                    'category_id'    => $orderProduct->product->category_id,
                    'sub_category_id'=> $orderProduct->product->sub_category_id,
                    'created_at'     => $orderProduct->product->created_at,
                    'updated_at'     => $orderProduct->product->updated_at,
                    'size'           => $orderProduct->size,
                    'quantity'       => $orderProduct->quantity,
                    'product_details'=> $orderProduct->productDetail ? [
                        'id'         => $orderProduct->productDetail->id,
                        'price'      => $orderProduct->productDetail->price,
                        'image'      => $orderProduct->productDetail->image ,
                        'color'      => $orderProduct->productDetail->color,
                        'size'       => $orderProduct->productDetail->size,
                        'typeprice'  => $orderProduct->productDetail->typeprice,
                        'typeimage'  => $orderProduct->productDetail->typeimage ,
                        'typename'   => $orderProduct->productDetail->typename,
                        'created_at' => $orderProduct->productDetail->created_at,
                        'updated_at' => $orderProduct->productDetail->updated_at,
                    ] : null, // If productDetail is null, return null for product_details
                ];
            })->toArray(),
        ];
    }
    
    public function getOrderById($orderId)
    {
        // Fetch the order with its relationships
        $order = Order::with(['orderProducts.product', 'orderStatus', 'paymentStatus', 'user', 'address'])
                      ->find($orderId);
    
        // Check if the order exists
        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found',
            ], 404);
        }
    
        // Return the order as a single resource
        return response()->json([
            'status' => true,
            'order' => new OrderResource($order),
        ], 200);
    }
    public function getUserOrders()
    {
        $user = auth()->user();
        $orders = Order::with(['orderProducts.product', 'orderStatus', 'paymentStatus', 'user', 'address'])
                       ->where('user_id', $user->id)
                       ->get();
    
        return response()->json([
            'status' => true,
            'orders' => OrderResource::collection($orders),
        ], 200);
    }
}