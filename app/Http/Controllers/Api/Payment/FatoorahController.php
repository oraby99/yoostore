<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderProductResource;
use App\Http\Resources\ProductResource;
use App\Http\Services\FatoorahServices;
use App\Http\Utils\Notification as UtilsNotification;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\ProductDetail;
use Google_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FatoorahController extends Controller
{
    private $fatoorahServices;
    private $notification;
    public function __construct(FatoorahServices $fatoorahServices , UtilsNotification $notification)
    {
         $this->fatoorahServices = $fatoorahServices;
         $this->notification = $notification;
    }
    public static function sendFCMNotification($data, $credentialsFile)
    {
        try {
            $client = new Google_Client();
            $credentialsFilePath = storage_path('app/' . $credentialsFile);
            if (!file_exists($credentialsFilePath)) {
                \Log::error('Failed to load Firebase credentials file.');
                return ['error' => 'Failed to retrieve Firebase credentials'];
            }
            $client->setAuthConfig($credentialsFilePath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $token = $client->fetchAccessTokenWithAssertion();           
            if (is_null($token) || !isset($token['access_token'])) {
                \Log::error('Failed to retrieve access token.');
                return ['error' => 'Failed to retrieve access token'];
            }
            $access_token = $token['access_token'];
            $headers = [
                "Authorization: Bearer $access_token",
                'Content-Type: application/json'
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/yoo-store-ed4ba/messages:send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $postData = [
                "message" => [
                    "notification" => [
                        "title" => $data["notification"]["title"],
                        "body"  => $data["notification"]["body"],
                    ],
                    "token" => $data["registration_ids"][0],
                ]
            ];
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
            $response = curl_exec($ch);
            if ($response === false) {
                return ['error' => curl_error($ch)];
            }
            curl_close($ch);
            return ['response' => json_decode($response, true)];
        } catch (\Exception $e) {
            \Log::error('Exception: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
    private function createNotification($userId, $orderId, $message, $type = 'Order')
    {
        Notification::create([
            'user_id'  => $userId,
            'order_id' => $orderId,
            'message'  => $message,
            'type'     => $type,
        ]);
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
                'user_id'           => $user->id,
                'total_price'       => $invoiceData->InvoiceValue,
                'invoice_id'        => $invoiceData->InvoiceId,
                'order_status_id'   => 1,
                'payment_method'    => 'gateway',
                'payment_status_id' => 1,
                'address_id'        => $defaultAddress->id
            ]);
            $deviceToken = $user->device_token;
            // \Log::info('Device Token: ' . $deviceToken);
            // if (!$deviceToken) {
            //     return response()->json(['message' => 'No device token found.'], 400);
            // }
            $data = [
                "registration_ids" => [$deviceToken],
                "notification" => [
                    "title" => 'Yoo Store',
                    "body" => $user->name . ' create a new order'
                ],"data" => [
                    "invoice_id"  => (string)$order->invoice_id,
                    "order_id"    => (string)$order->id,
                    "type"        => "Received",
                ]
            ];
            $response = self::sendFCMNotification($data, 'yoo-store-ed4ba-de6f28257b6d.json');
            $this->createNotification($user->id, $order->id, 'New order created successfully.','Received');
            if (!empty($response['error'])) {
                \Log::error('FCM Error: ' . json_encode($response['error']));
                return response()->json(['message' => 'Error: ' . $response['error']], 500);
            }
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
        $defaultAddress = Address::where('user_id', $user->id)->where('is_default', 1)->first();
        if (!$defaultAddress) {
            return response()->json([
                'status'  => false,
                'message' => 'Default address not found.'
            ], 400);
        }
        $cartItems = Cart::where('user_id', $user->id)->get();
        if ($cartItems->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'No items found in cart.'
            ], 400);
        }
        \DB::beginTransaction();
        try {
            $totalPrice = $request->total ?? $cartItems->sum('total_price');
            $order = Order::create([
                'user_id'           => $user->id,
                'total_price'       => $totalPrice,
                'order_status_id'   => 1,
                'payment_method'    => 'cod',
                'payment_status_id' => 1,
                'address_id'        => $defaultAddress->id
            ]);
            foreach ($cartItems as $item) {
                OrderProduct::create([
                    'order_id'         => $order->id,
                    'product_id'       => $item->product_id,
                    'quantity'         => $item->quantity,
                ]);
            }
            Cart::where('user_id', $user->id)->delete();
            // Load the necessary relationships
            $order->load(['orderStatus', 'paymentStatus', 'orderProducts.product.childproduct']);
            // Prepare the products array - with fix for duplication
            $addedProductIds = [];
            $products = [];
     
            foreach ($order->orderProducts as $orderProduct) {
                $product = $orderProduct->product;
                $productId = $product->id;
                
                // Skip if this product was already added
                if (in_array($productId, $addedProductIds)) {
                    continue;
                }
                
                // Add this product ID to the list of added products
                $addedProductIds[] = $productId;
                
                // Filter child products based on what was added to the cart
                $filteredChildProducts = $product->childproduct->filter(function ($childProduct) use ($cartItems) {
                    return $cartItems->contains('product_id', $childProduct->id);
                });
                
                // If the product has child products, add them to the response
                if ($filteredChildProducts->isNotEmpty()) {
                    // Track child product IDs to avoid adding them separately
                    foreach ($filteredChildProducts as $childProduct) {
                        $addedProductIds[] = $childProduct->id;
                    }
                    
                    $product->childproduct = $filteredChildProducts;
                    $products[] = new ProductResource($product, $user->id);
                } else {
                    // If it's a product with no child products in the cart, add it directly
                    $products[] = new ProductResource($product, $user->id);
                }
            }
            \DB::commit();
            return response()->json([
                'status'  => true,
                'message' => 'Order placed successfully under Cash on Delivery',
                'order'   => [
                    'id'              => $order->id,
                    'user_id'         => $order->user_id,
                    'address_id'      => $order->address_id,
                    'total_price'     => $order->total_price,
                    'status'          => $order->orderStatus->name,
                    'payment_method'  => $order->payment_method,
                    'payment_status'  => $order->paymentStatus->name,
                    'created_at'      => $order->created_at,
                    'updated_at'      => $order->updated_at,
                    'products'        => $products
                ]
            ], 200);
    
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('COD Checkout Error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'Failed to place order. Please try again. ' . $e->getMessage()
            ], 500);
        }
    }
    public function errorurl(Request $request)
    {    
        $paymentId = $request->query('paymentId');
        return redirect()->route('payment.failure')->with('message', 'Payment process failed')->with('paymentId', $paymentId);
    }
}
