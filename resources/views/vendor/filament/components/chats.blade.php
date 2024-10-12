@php
    $chatsGroupedByUser = $chats->groupBy('user.name');
@endphp
<div class="space-y-6">
    @foreach ($chatsGroupedByUser as $userName => $chatsByUser)
        <div class="border rounded-lg p-4 bg-dark shadow-md">
            <h2 class="font-bold text-lg">{{ $userName }}</h2>
            @php
                $chatsGroupedByProduct = $chatsByUser->groupBy('product.name');
            @endphp
            @foreach ($chatsGroupedByProduct as $productName => $chatsByProduct)
                <div class="mt-4">
                    <h3 class="font-semibold">{{ $productName }}</h3>
                    <div class="space-y-2 mt-2 bg-dark">
                        @foreach ($chatsByProduct as $chat)
                            <div class="p-2 bg-gray-100 rounded chat-bubble">
                                <p class="font-semibold text-sm" style="color: black">{{ $chat->message }}</p>
                                <p class="text-gray-500 text-sm">Reply: {{ $chat->reply ?? 'No reply yet' }}</p>
                                <p class="text-xs text-gray-400">{{ $chat->created_at->format('d M Y, H:i') }}</p>
                                
                                <!-- Edit Button -->
                                <a href="{{ route('filament.admin.resources.chats.edit', $chat->id) }}"
                                   class="mt-2 text-sm text-blue-500 hover:underline">
                                    Edit
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>
