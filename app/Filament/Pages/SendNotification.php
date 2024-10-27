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

    public $user_id;
    public $message;

    protected function getFormSchema(): array
    {
        return [
            Select::make('user_id')
                ->label('Select User')
                ->options(User::pluck('name', 'id'))
                ->searchable()
                ->placeholder('Select a user to notify'),
                
            Textarea::make('message')
                ->label('Message')
                ->required(),
        ];
    }

    public function sendNotification()
    {
        $user = User::find($this->user_id);
        if ($user && $user->device_token) {
            $data = [
                "registration_ids" => [$user->device_token],
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
        } else {
            Notification::make()
                ->title('User not found or device token missing.')
                ->danger()
                ->send();
        }
    }
}
