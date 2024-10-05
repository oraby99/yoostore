<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Http\Services\FatoorahServices;
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
        $totalPrice = 100.00;
        $data = [
            "CustomerName"       =>  $user->name,
            "Notificationoption" =>  "LNK",  
            "Invoicevalue"       =>  $totalPrice,
            "CustomerEmail"      =>  $user->email,     
            "CalLBackUrl"        =>  env('CalLBackUrl'),
            "Errorurl"           =>  env('Errorurl'),  
            "Languagn"           =>  'en',
            "DisplayCurrencyIna" =>  'SAR'
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
        $invoiceData = $response->Data;
        if (!isset($response->Data->InvoiceId)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid payment or payment not found'
            ], 404);
        }
        if ($response->IsSuccess && $invoiceData->InvoiceStatus == "Paid") {
            return response()->json([
                'success'        => true,
                'invoice_id'     => $invoiceData->InvoiceId,
                'invoice_status' => 'Paid',
                'message'        => 'Payment successful'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Payment failed or not completed',
            ], 400);
        }
    }
    public function errorurl(Request $request)
    {
        $paymentId = $request->query('paymentId');
        return response()->json([
            'success' => false,
            'message' => 'Payment process failed',
            'paymentId' => $paymentId
        ], 400);
    }
}
