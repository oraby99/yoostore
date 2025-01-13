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
use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

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
                TextInput::make('product_id')
                    ->label('Product ID')
                    ->required()
                    ->disabled(),
                TextInput::make('name')
                    ->label('Name')
                    ->required(),
                TextInput::make('title_ar')
                    ->label('Title Ar')
                    ->required(),
                RichEditor::make('description')
                    ->label('Description')
                    ->required(),
                RichEditor::make('short_description')
                    ->label('Short Description')
                    ->required(),
                RichEditor::make('description_ar')
                    ->label('Description Ar')
                    ->required(),
                TextInput::make('stock')
                    ->label('Stock')
                    ->numeric()
                    ->required(),
                TextInput::make('discount')
                    ->label('Discount')
                    ->numeric()
                    ->required(),
                TextInput::make('delivery_time')
                    ->label('Delivery Time')
                    ->numeric()
                    ->required(),
                TextInput::make('tags')
                    ->label('Tags')
                    ->required(),
                TextInput::make('sku')
                    ->label('SKU')
                    ->required(),
                Select::make('categories')
                    ->label('Categories')
                    ->options(Category::all()->pluck('name', 'name')->toArray())
                    ->reactive()
                    ->required(),
                // Repeater::make('images')
                //     ->label('Images')
                //     ->schema([
                //         TextInput::make('url')
                //             ->label('Image URL')
                //             ->required(),
                //     ])
                //     ->columns(1)
                //     ->defaultItems(1)
                //     ->afterStateHydrated(function ($state, callable $set) {
                //         $images = is_array($state) ? $state : explode(',', $state);
                //         $images = array_map('trim', $images);
                //         $set('images', collect($images)->map(fn($url) => ['url' => $url])->toArray());
                //     })
                //     ->dehydrateStateUsing(function ($state) {
                //         $filteredUrls = collect($state)
                //             ->pluck('url')
                //             ->filter(function ($url) {
                //                 return !empty($url);
                //             })
                //             ->toArray();
                //         return implode(',', $filteredUrls);
                //     }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product_id')->label('Product ID'),
                Tables\Columns\TextColumn::make('name')->label('Name'),
                Tables\Columns\TextColumn::make('sku')->label('SKU'),
                ImageColumn::make('images')
                    ->label('Images')
                    ->getStateUsing(function ($record) {
                        $images = is_array($record->images) ? $record->images : explode(',', $record->images);
                        return $images[0] ?? null;
                    })
                    ->size(50),
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
                Action::make('import')
                    ->label('Import Products')
                    ->form([
                        FileUpload::make('file')
                            ->label('CSV File')
                            ->required()
                            ->acceptedFileTypes(['text/csv', 'text/plain'])
                            ->directory('imports')
                            ->preserveFilenames(),
                    ])
                    ->action(function (array $data) {
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