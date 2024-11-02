<?php

namespace App\Http\Controllers\Api\Chat;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ChatResource;
use App\Http\Resources\ProductResource;
use App\Models\Notification;

class ChatController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'message' => 'required|string',
            'chat_id' => 'nullable|exists:chats,id'
        ]);
        $product = Product::find($validated['product_id']);
        if (!$product) {
            return ApiResponse::send(false, 'Product not found', null);
        }
        if ($request->chat_id) {
            $chat = Chat::find($request->chat_id);
            if (!$chat || $chat->user_id !== $user->id) {
                return ApiResponse::send(false, 'Chat not found or unauthorized', null);
            }
            $newChat = Chat::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'message' => $validated['message'],
            ]);
        } else {
            $newChat = Chat::create([
                'user_id' => $user->id,
                'product_id' => $validated['product_id'],
                'message' => $validated['message'],
            ]);
        }
        return ApiResponse::send(true, 'Message saved', new ChatResource($newChat));
    }    
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);
        $chat = Chat::find($id);
        if (!$chat || $chat->user_id !== auth()->id()) {
            return ApiResponse::send(false, 'Chat not found or unauthorized', null);
        }
        $chat->message = $validated['message'];
        $chat->save();
        return ApiResponse::send(true, 'Message updated', new ChatResource($chat));
    }
    public function destroy($id)
    {
        $chat = Chat::find($id);
        if (!$chat || $chat->user_id !== auth()->id()) {
            return ApiResponse::send(false, 'Chat not found or unauthorized', null);
        }
        $chat->delete();
        return ApiResponse::send(true, 'Chat deleted');
    }
    public function index()
    {
        $user = auth()->user();
        $chats = Chat::where('user_id', $user->id)
            ->with('product')
            ->get()
            ->groupBy('product_id');
        if ($chats->isEmpty()) {
            return ApiResponse::send(false, 'No chats found');
        }
        $formattedChats = [];
        foreach ($chats as $productId => $chatGroup) {
            $product = $chatGroup->first()->product;
            $lastMessage = $chatGroup->last();
            $formattedChats[] = [
                'last_message' => new ChatResource($lastMessage),
            ];
        }
        return ApiResponse::send(true, 'Chats retrieved successfully', $formattedChats);
    }
    public function getChatByUserAndProduct(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        $user = auth()->user();
        $chats = Chat::where('user_id', $user->id)
            ->where('product_id', $validated['product_id'])
            ->get();
        if ($chats->isEmpty()) {
            return ApiResponse::send(false, 'No chats found for this product');
        }
        $product = Product::find($validated['product_id']);
        if (!$product) {
            return ApiResponse::send(false, 'Product not found');
        }
        $response = [
            'product' => new ProductResource($product),
            'chats' => ChatResource::collection($chats),
        ];
        return ApiResponse::send(true, 'Chats retrieved successfully', $response);
    }
    public function notification()
    {
        $user = auth()->user();
        $notifications = Notification::where('user_id', $user->id)
            ->with('order:id,invoice_id')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $data = $notifications->getCollection()->map(function ($notification) {
            $createdDate = $notification->created_at;
            if ($createdDate->isToday()) {
                $formattedDate = 'Today';
            } elseif ($createdDate->isYesterday()) {
                $formattedDate = 'Yesterday';
            } else {
                $formattedDate = $createdDate->format('j M');
            }
            return [
                'id'          => $notification->id,
                'title'       => $notification->title,
                'user_id'     => $notification->user_id,
                'order_id'    => $notification->order_id,
                'invoice_id'  => $notification->order->invoice_id ?? null,
                'message'     => $notification->message,
                'type'        => $notification->type,
                'created_at'  => $formattedDate,
                'updated_at'  => $notification->updated_at,
            ];
        });
        return response()->json([
            'data'    => $data,
            'status'  => 200,
            'message' => 'success',
            'pagination' => [
                'total'        => $notifications->total(),
                'per_page'     => $notifications->perPage(),
                'current_page' => $notifications->currentPage(),
                'last_page'    => $notifications->lastPage(),
                'next_page_url' => $notifications->nextPageUrl(),
                'prev_page_url' => $notifications->previousPageUrl(),
            ],
        ]);
    }
    
}
