<?php

namespace App\Filament\Resources\OrderCancellationResource\Pages;

use App\Filament\Resources\OrderCancellationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrderCancellation extends CreateRecord
{
    protected static string $resource = OrderCancellationResource::class;
}
