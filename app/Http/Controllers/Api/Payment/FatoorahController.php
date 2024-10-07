<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Http\Services\FatoorahServices;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;

class FatoorahController extends Controller
{
    private $fatoorahServices;
    public function __construct(FatoorahServices $fatoorahServices)
    {
         $this->fatoorahServices = $fatoorahServices;
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
        
        // Retrieve the default address
        $defaultAddress = Address::where('user_id', $user->id)
                                 ->where('is_default', 1)
                                 ->first();
        
        if (!$defaultAddress) {
            return response()->json([
                'status'  => false,
                'message' => 'Default address not found.'
            ], 400);
        }
    
        // Start a database transaction
        \DB::beginTransaction();
        
        try {
            // Calculate total price from request or cart
            $totalPrice = $request->total ?? Cart::where('user_id', $user->id)->sum('total_price');
            
            // Create the order
            $order = Order::create([
                'user_id'        => $user->id,
                'total_price'    => $totalPrice,
                'status'         => 'Received', // Status set to Received for COD
                'payment_method' => 'cod',      // Payment method is COD
                'payment_status' => 'Pending',  // Payment pending
                'address_id'     => $defaultAddress->id
            ]);
    
            // Retrieve all cart items
            $cartItems = Cart::where('user_id', $user->id)->get();
    
            if ($cartItems->isEmpty()) {
                // If no cart items, roll back and return an error
                \DB::rollBack();
                return response()->json([
                    'status'  => false,
                    'message' => 'No items found in cart.'
                ], 400);
            }
    
            // Create order products
            foreach ($cartItems as $item) {
                OrderProduct::create([
                    'order_id'   => $order->id,
                    'product_detail_id' => $item->product_detail_id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'size'       => $item->size,
                ]);
            }
    
            // Clear the user's cart after order creation
            Cart::where('user_id', $user->id)->delete();
    
            // Commit the transaction
            \DB::commit();
    
            // Return a success response
            return response()->json([
                'status'   => true,
                'message'  => 'Order placed successfully under Cash on Delivery',
                'order_id' => $order->id,
                'order'    => $order
            ], 200);
        
        } catch (\Exception $e) {
            // Rollback on error
            \DB::rollBack();
            \Log::error('COD Checkout Error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'Failed to place order. Please try again.'
            ], 500);
        }
    }
    public function errorurl(Request $request)
    {    
        $paymentId = $request->query('paymentId');
        return redirect()->route('payment.failure')->with('message', 'Payment process failed')->with('paymentId', $paymentId);
    }
}
