<?php

namespace App\Filament\Resources\ImportedProductResource\Pages;

use App\Filament\Resources\ImportedProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateImportedProduct extends CreateRecord
{
    protected static string $resource = ImportedProductResource::class;
}
