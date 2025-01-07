<?php

namespace App\Filament\Resources\ImportedProductResource\Pages;

use App\Filament\Resources\ImportedProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImportedProduct extends EditRecord
{
    protected static string $resource = ImportedProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
