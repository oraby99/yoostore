<?php

namespace App\Filament\Resources\ChatResource\Pages;

use App\Filament\Resources\ChatResource;
use Filament\Resources\Pages\Page;
use App\Models\Chat;

class CustomChatView extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat';
    protected static string $view = 'vendor.filament.components.chats';
    protected static string $resource = ChatResource::class;

    public $chats;

    public function mount(): void
    {
        $this->chats = Chat::with(['user', 'product'])->get();
    }

    protected function getViewData(): array
    {
        return [
            'chats' => $this->chats,
        ];
    }
}
