<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ImportedProductResource\Pages;
use App\Models\ImportedProduct;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportedProductsImport;
use Filament\Forms\Components\FileUpload;

class ImportedProductResource extends Resource
{
    protected static ?string $model = ImportedProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Import Products';
    protected static ?string $navigationGroup = 'Products';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Define form fields here (optional)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product_id')->label('Product ID'),
                Tables\Columns\TextColumn::make('name')->label('Name'),
                Tables\Columns\TextColumn::make('sku')->label('SKU'),
                Tables\Columns\TextColumn::make('type')->label('Type'),
                Tables\Columns\TextColumn::make('categories')->label('Categories'),
                Tables\Columns\TextColumn::make('tags')->label('Tags'),
            ])
            ->filters([
                Tables\Filters\Filter::make('published')
                    ->query(fn (Builder $query) => $query->where('published', true))
                    ->label('Published Only'),
            ])
            ->headerActions([
                // Add custom import action to the header
                Action::make('import')
                    ->label('Import Products')
                    ->form([
                        FileUpload::make('file')
                            ->label('CSV File')
                            ->required()
                            ->acceptedFileTypes(['text/csv', 'text/plain'])
                            ->directory('imports') // Save files to the "imports" directory
                            ->preserveFilenames(), // Preserve the original file name
                    ])
                    ->action(function (array $data) {
                        // Handle file upload and import
                        $filePath = storage_path('app/public/' . $data['file']);
                        Excel::import(new ImportedProductsImport, $filePath);
                    })
                    ->successNotificationTitle('Products imported successfully!'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Define relations here (optional)
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListImportedProducts::route('/'),
            'create' => Pages\CreateImportedProduct::route('/create'),
            'edit' => Pages\EditImportedProduct::route('/{record}/edit'),
        ];
    }
}