<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Http\Services\FatoorahServices;
use App\Http\Utils\Notification as UtilsNotification;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;

class FatoorahController extends Controller
{
    private $fatoorahServices;
    private $notification;
    public function __construct(FatoorahServices $fatoorahServices , UtilsNotification $notification)
    {
         $this->fatoorahServices = $fatoorahServices;
         $this->notification = $notification;
    }
    public function checkout(Request $request)
    {
        $user = auth()->user();
        $totalPrice = $request->total;
        $data = [
            "CustomerName"       => $user->name,
            "Notificationoption" => "LNK",
            "Invoicevalue"       => $totalPrice,
            "CustomerEmail"      => $user->email,
            "CalLBackUrl"        => url('/callback?user_id=' . $user->id),
            "Errorurl"           => url('/errorurl'),
            "Languagn"           => 'en',
            "DisplayCurrencyIna" => 'SAR'
        ];
        $response = $this->fatoorahServices->sendPayment($data);
        if (isset($response['IsSuccess']) && $response['IsSuccess'] == true) {
            return response()->json([
                'success'     => true,
                'invoice_url' => $response['Data']['InvoiceURL'],
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => $response['Message'] ?? 'Payment initiation failed',
                'errors'  => $response['ValidationErrors'] ?? 'Unknown error',
            ], 400);
        }
    }
    public function callback(Request $request)
    {
        $apiKey = env("fatoora_token");
        $postFields = [
            'Key'     => $request->paymentId,
            'KeyType' => 'paymentId'
        ];
        $response = $this->fatoorahServices->callAPI("https://apitest.myfatoorah.com/v2/getPaymentStatus", $apiKey, $postFields);
        $response = json_decode($response);
        
        if (!$response || !$response->IsSuccess) {
            return redirect()->route('payment.failure')->with('message', 'Failed to fetch payment status');
        }
    
        $invoiceData = $response->Data ?? null;
        if (!$invoiceData || !isset($invoiceData->InvoiceId)) {
            return redirect()->route('payment.failure')->with('message', 'Invoice not found');
        }
    
        $userId = $request->query('user_id');
        $user = \App\Models\User::find($userId);
    
        if (!$user) {
            return redirect()->route('payment.failure')->with('message', 'User not found');
        }
    
        if ($invoiceData->InvoiceStatus === "Paid") {
            $defaultAddress = Address::where('user_id', $user->id)->where('is_default', 1)->first();
            $order = Order::create([
                'user_id'        => $user->id,
                'total_price'    => $invoiceData->InvoiceValue,
                'invoice_id'     => $invoiceData->InvoiceId,
                'status'         => 'Received',
                'payment_method' => 'gateway',
                'payment_status' => 'Paid',
                'address_id'     => $defaultAddress->id
            ]);
            $result =  $this->notification->send('Received',$user->id,$user->device_token,$order->id);
            $cartItems = Cart::where('user_id', $user->id)->get();
            foreach ($cartItems as $item) {
                OrderProduct::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'product_detail_id' => $item->product_detail_id,
                    'quantity'   => $item->quantity,
                    'size'       => $item->size,
                ]);
            }
    
            Cart::where('user_id', $user->id)->delete();
    
            // Redirect to the success page with both invoiceId and paymentId as query parameters
            return redirect()->route('payment.success', [
                'invoiceId' => $invoiceData->InvoiceId,
                'paymentId' => $request->paymentId
            ]);
        } else {
            return redirect()->route('payment.failure')->with('message', 'Payment failed');
        }
    }
    public function codCheckout(Request $request)
    {
        $user = auth()->user();
        $defaultAddress = Address::where('user_id', $user->id)
                                 ->where('is_default', 1)
                                 ->first();
    
        if (!$defaultAddress) {
            return response()->json([
                'status'  => false,
                'message' => 'Default address not found.'
            ], 400);
        }
    
        \DB::beginTransaction();
        try {
            $totalPrice = $request->total ?? Cart::where('user_id', $user->id)->sum('total_price');
            $order = Order::create([
                'user_id'        => $user->id,
                'total_price'    => $totalPrice,
                'status'         => 'Received',
                'payment_method' => 'cod',
                'payment_status' => 'Pending',
                'address_id'     => $defaultAddress->id
            ]);
            $result =  $this->notification->send('Received',$user->id,$user->device_token,$order->id);
            $cartItems = Cart::where('user_id', $user->id)->get();
            if ($cartItems->isEmpty()) {
                \DB::rollBack();
                return response()->json([
                    'status'  => false,
                    'message' => 'No items found in cart.'
                ], 400);
            }
    
            foreach ($cartItems as $item) {
                OrderProduct::create([
                    'order_id'   => $order->id,
                    'product_detail_id' => $item->product_detail_id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'size'       => $item->size,
                    'result'     => $result
                ]);
            }
    
            // Delete cart items after creating the order
            Cart::where('user_id', $user->id)->delete();
    
            // Fetch the order products and details for the created order
            $orderProducts = OrderProduct::with(['product', 'productDetail'])
                ->where('order_id', $order->id)
                ->get();
    
            // Organize the order product data similar to the getUserOrders structure
            $productsGrouped = [];
            foreach ($orderProducts as $orderProduct) {
                if ($orderProduct->product && $orderProduct->productDetail) {
                    $uniqueProductKey = $orderProduct->product_id . '-' . $orderProduct->product_detail_id;
                    $productsGrouped[$uniqueProductKey] = [
                        'id' => $orderProduct->product_id,
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
                        'size' => $orderProduct->size,
                        'quantity' => $orderProduct->quantity,
                        'product_details' => 
                            [
                                'id' => $orderProduct->productDetail->id,
                                'price' => $orderProduct->productDetail->price,
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
                            
                        ]
                    ];
                }
            }
    
            \DB::commit();
    
            // Return response with order and product details
            return response()->json([
                'status'   => true,
                'message'  => 'Order placed successfully under Cash on Delivery',
                'order'    => [
                    'id' => $order->id,
                    'user_id' => $order->user_id,
                    'address_id' => $order->address_id,
                    'total_price' => $order->total_price,
                    'status' => $order->status,
                    'payment_method' => $order->payment_method,
                    'payment_status' => $order->payment_status,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at,
                    'products' => array_values($productsGrouped)
                ]
            ], 200);
    
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('COD Checkout Error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'Failed to place order. Please try again.' . $e->getMessage()
            ], 500);
        }
    }
    public function errorurl(Request $request)
    {    
        $paymentId = $request->query('paymentId');
        return redirect()->route('payment.failure')->with('message', 'Payment process failed')->with('paymentId', $paymentId);
    }
}
