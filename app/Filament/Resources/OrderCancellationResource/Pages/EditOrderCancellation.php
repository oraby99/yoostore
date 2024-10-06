<?php

namespace App\Filament\Resources\OrderCancellationResource\Pages;

use App\Filament\Resources\OrderCancellationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrderCancellation extends EditRecord
{
    protected static string $resource = OrderCancellationResource::class;

    protected function getHeaderActions(): array
    {
        return [
           // Actions\DeleteAction::make(),
        ];
    }
}
