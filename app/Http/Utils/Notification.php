<?php

namespace App\Http\Utils;

use App\Models\User;
use Google_Client;
use Illuminate\Support\Facades\Http;

class Notification{
    public static function send($type, $id, $token, $order_id)
    {
        $user = User::find($id);
        $message = $type == 'Received' . 'Congratulations, your Order request has been approved';
        $fcm = $user->device_token;    
        $credentialsFilePath = storage_path('yoo-store-ed4ba-de6f28257b6d.json');
        $client = new Google_Client();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');        
        $token = $client->getAccessToken();
        $access_token = $token['access_token'] ?? null;    
        if (!$access_token) {
            return response()->json(['message' => 'Failed to get access token.'], 500);
        }
        $headers = [
            "Authorization: Bearer $access_token",
            'Content-Type: application/json'
        ];
        $data = [
            "message" => [
                "token" => $fcm,
                "notification" => [
                    "title" => 'Yoo Store',
                    "body" => $message,
                ],
                "data" => [
                    "order_id" => (string)$order_id,
                    "type" => $type == 'Received',
                ],
            ]
        ];
        $payload = json_encode($data);    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/yoo-store-ed4ba/messages:send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if ($err) {
            return response()->json([
                'message' => 'Curl Error: ' . $err
            ], 500);
        } else {
            \App\Models\Notification::create([
                'user_id' => $id,
                'order_id' => $order_id,
                'type' => $type,
                'message' => $message
            ]);
            return response()->json([
                'message' => 'Notification has been sent',
                'response' => json_decode($response, true),
                'data' => $data
            ]);
        }
    } 
}