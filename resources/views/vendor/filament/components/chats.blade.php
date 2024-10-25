@php
    $chatsGroupedByUser = $chats->groupBy('user.name');
@endphp
<div class="space-y-6">
    @foreach ($chatsGroupedByUser as $userName => $chatsByUser)
        <div class="border rounded-lg p-4 bg-dark shadow-md">
            <h2 class="font-bold text-lg">User Name : {{ $userName }}</h2><br>
            @php
                $chatsGroupedByProduct = $chatsByUser->groupBy('product.name');
            @endphp
            @foreach ($chatsGroupedByProduct as $productName => $chatsByProduct)
                <div class="mt-4">
                    <h3 class="font-semibold">Product Name : {{ $productName }}</h3>
                    <div class="space-y-2 mt-2 bg-dark">
                        @foreach ($chatsByProduct as $chat)
                            <div class="p-2 rounded chat-bubble" style="background-color: rgb(86, 87, 95)">
                                <p class="font-semibold text-sm" style="color: black">Message : {{ $chat->message }}</p>
                                <p class="text-gray-500 text-sm" style="color: black">Reply: {{ $chat->reply ?? 'No reply yet' }}</p>
                                <p class="text-xs text-gray-400" style="color: black">Date : {{ $chat->created_at->format('d M Y, H:i') }}</p>
                                <a href="{{ route('filament.admin.resources.chats.edit', $chat->id) }}">
                                    <button style="background-color: black; border-radius: 10%;color: orange">Replay</button>
                                </a>
                            </div>
                        @endforeach<br>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>
