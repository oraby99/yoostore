<?php

namespace App\Http\Controllers\Api\Chat;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ChatResource;

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
            $formattedChats[] = [
                'product_id' => $productId,
                'chats' => ChatResource::collection($chatGroup),
            ];
        }
        return ApiResponse::send(true, 'Chats retrieved successfully', $formattedChats);
    }
}
