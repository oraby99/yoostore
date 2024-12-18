<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    use ListRecords\Concerns\Translatable;
    protected static string $resource = ProductResource::class;
    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make(),
            \EightyNine\ExcelImport\ExcelImportAction::make()
            ->color("primary"),
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
               \EightyNine\ExcelImport\ExcelImportAction::make()
            ->slideOver()
            ->color("primary")
            ->use(\App\Imports\ProductImport::class),
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
