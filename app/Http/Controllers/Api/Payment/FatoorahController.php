<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Http\Services\FatoorahServices;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
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
            Cart::where('user_id', $user->id)->delete();
            Order::create([
                'user_id'        => $user->id,
                'total_price'    => $invoiceData->InvoiceValue,
                'invoice_id'     => $invoiceData->InvoiceId,
                'status'         => 'recived',
                'payment_method' => 'gateway',
                'payment_status' => 'Paid',
                'address_id'     => $defaultAddress->id
            ]);
            return redirect()->route('payment.success')->with('invoiceId', $invoiceData->InvoiceId);
        } else {
            return redirect()->route('payment.failure')->with('message', 'Payment failed');
        }
    }    
    public function errorurl(Request $request)
    {    
        $paymentId = $request->query('paymentId');
        return redirect()->route('payment.failure')->with('message', 'Payment process failed')->with('paymentId', $paymentId);
    }
    public function codCheckout(Request $request)
    {
        $user = auth()->user();
        Cart::where('user_id', $user->id)->delete();
        $totalPrice = $request->total;
        $defaultAddress = Address::where('user_id', $user->id)->where('is_default', 1)->first();
        $order = Order::create([
            'user_id'        => $user->id,
            'total_price'    => $totalPrice,
            'status'         => 'recived',
            'payment_method' => 'cod',
            'payment_status' => 'paid',
            'address_id'     => $defaultAddress->id
        ]);
        return response()->json([
            'status'   => true,
            'message'  => 'Order placed successfully under Cash on Delivery',
            'order_id' => $order->id,
            'order'    => $order
        ], 200);
    }
}
