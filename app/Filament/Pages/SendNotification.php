<?php

namespace App\Filament\Pages;

use App\Http\Controllers\Api\Payment\FatoorahController;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;

class SendNotification extends Page
{
    protected static ?string $navigationLabel = 'Send Notification';
    protected static ?string $slug = 'send-notification';
    protected static ?string $title = 'Send Notification';
    protected static string $view = 'filament.pages.send-notification';
    protected static ?string $navigationGroup = 'Notification';

    public $user_id;
    public $message;

    protected function getFormSchema(): array
    {
        return [
            Select::make('user_id')
                ->label('Select User')
                ->options(User::pluck('name', 'id')->prepend('All Users', 'all'))
                ->searchable()
                ->placeholder('Select a user or all users to notify'),

            Textarea::make('message')
                ->label('Message')
                ->required(),
        ];
    }

    public function sendNotification()
    {
        if ($this->user_id === 'all') {
            // Send to all users
            $users = User::whereNotNull('device_token')->get();
            $deviceTokens = $users->pluck('device_token')->toArray();

            if (empty($deviceTokens)) {
                Notification::make()
                    ->title('No users with device tokens found.')
                    ->danger()
                    ->send();
                return;
            }

        } else {
            // Send to a single selected user
            $user = User::find($this->user_id);

            if (!$user || !$user->device_token) {
                Notification::make()
                    ->title('User not found or device token missing.')
                    ->danger()
                    ->send();
                return;
            }

            $deviceTokens = [$user->device_token];
        }

        $data = [
            "registration_ids" => $deviceTokens,
            "notification" => [
                "title" => 'Custom Notification',
                "body"  => $this->message,
            ],
        ];

        $response = FatoorahController::sendFCMNotification($data, 'yoo-store-ed4ba-de6f28257b6d.json');

        if (!empty($response['error'])) {
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