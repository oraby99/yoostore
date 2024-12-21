<x-filament-panels::page>
    <div class="space-y-6">
        @php
            $chatsGroupedByUser = $chats->groupBy('user.name');
        @endphp
        @foreach ($chatsGroupedByUser as $userName => $chatsByUser)
            <div class="border rounded-lg p-4 bg-gray-100 shadow-md">
                <!-- User Section -->
                <h2 
                    class="font-bold text-lg cursor-pointer" 
                    onclick="toggleSection('user-{{ str_replace(' ', '-', $userName) }}')">
                    User Name: {{ $userName }}
                    <span class="ml-2">+</span>
                </h2>
                <div 
                    class="hidden mt-4 space-y-4" 
                    id="user-{{ str_replace(' ', '-', $userName) }}">

                    @php
                        $chatsGroupedByProduct = $chatsByUser->groupBy('product.name');
                    @endphp

                    @foreach ($chatsGroupedByProduct as $productName => $chatsByProduct)
                        <!-- Product Section -->
                        <div class="border rounded-lg p-3 bg-gray-200">
                            <h3 
                                class="font-semibold cursor-pointer" 
                                onclick="toggleSection('product-{{ str_replace(' ', '-', $userName . '-' . $productName) }}')">
                                Product Name: {{ $productName }}
                                <span class="ml-2">+</span>
                            </h3>
                            <div 
                                class="hidden mt-3 space-y-3" 
                                id="product-{{ str_replace(' ', '-', $userName . '-' . $productName) }}">

                                @foreach ($chatsByProduct as $chat)
                                    <!-- Chat Section -->
                                    <div class="p-3 rounded bg-gray-300">
                                        <p><strong>Message:</strong> {{ $chat->message }}</p>
                                        <p><strong>Reply:</strong> {{ $chat->reply ?? 'No reply yet' }}</p>
                                        <p class="text-sm text-gray-600">Date: {{ $chat->created_at->format('d M Y, H:i') }}</p>
                                        <a href="{{ route('filament.admin.resources.chats.edit', $chat->id) }}">
                                            <button class="bg-blue-500 text-white rounded px-3 py-1">Reply</button>
                                        </a>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        @endforeach
    </div>
    <script>
        function toggleSection(sectionId) {
            const section = document.getElementById(sectionId);
            section.classList.toggle('hidden');
            const toggleIcon = section.previousElementSibling.querySelector('span');
            toggleIcon.textContent = section.classList.contains('hidden') ? '+' : '-';
        }
    </script>
    <style>
        .cursor-pointer {
            cursor: pointer;
        }
        .hidden {
            display: none;
        }
    </style>
</x-filament-panels::page>
