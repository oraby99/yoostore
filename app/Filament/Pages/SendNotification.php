<?php

namespace App\Filament\Pages;

use App\Http\Controllers\Api\Payment\FatoorahController;
use App\Models\Notification as ModelsNotification;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

class SendNotification extends Page
{
    protected static ?string $navigationLabel = 'Send Notification';
    protected static ?string $slug = 'send-notification';
    protected static string $view = 'filament.pages.send-notification';
    protected static ?string $navigationGroup = 'Notification';

    public $user_id;
    public $message;
    public $title; // Add this line

    protected function getFormSchema(): array
    {
        return [
            Select::make('user_id')
                ->label('Select User')
                ->options(User::pluck('name', 'id')->prepend('All Users', 'all'))
                ->searchable()
                ->placeholder('Select a user or all users to notify'),
    
            TextInput::make('title')
                ->label('Notification Title')
                ->required(),
    
            Textarea::make('message')
                ->label('Message')
                ->required(),
        ];
    }

    public function createNotification($userId, $orderId = null, $title, $message, $type = 'Order')
    {
        ModelsNotification::create([
            'user_id'  => $userId,
            'order_id' => $orderId,
            'title'    => $title,
            'message'  => $message,
            'type'     => $type,
        ]);
    }

    public function sendNotification()
    {
        $tokens = [];
        $users = [];
        
        if ($this->user_id === "all") {
            $users = User::whereNotNull('device_token')->get(['id', 'device_token']);
            if ($users->isEmpty()) {
                Notification::make()
                    ->title('No users with device tokens found.')
                    ->danger()
                    ->send();
                return;
            }
        } else {
            $user = User::find($this->user_id);
            if (!$user || !$user->device_token) {
                Notification::make()
                    ->title('User not found or device token missing.')
                    ->danger()
                    ->send();
                return;
            }
            $users = collect([$user]);
        }
    
        foreach ($users as $user) {
            $data = [
                "registration_ids" => [$user->device_token],
                "notification" => [
                    "title" => $this->title,
                    "body" => $this->message,
                ],
            ];
            $response = FatoorahController::sendFCMNotification($data, 'yoo-store-ed4ba-de6f28257b6d.json');
    
            // Log notification in the database for each user
            $this->createNotification($user->id, null, $this->title, $this->message, 'Custom');
        }
    
        // Handle FCM response
        if (isset($response['error']) && !empty($response['error'])) {
            Notification::make()
                ->title('Failed to send notification.')
                ->danger()
                ->send();
        } else {
            Notification::make()
                ->title('Notification sent successfully.')
                ->success()
                ->send();
        }
    }
}
