<?php
namespace App\Filament\Resources;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Category;
use App\Models\Product;
use App\Models\sub_category;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
class ProductResource extends Resource
{
    use Translatable;
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Products';
    public static function form(Form $form): Form
    {
        $categories = sub_category::all()->pluck('name', 'id');
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('description')->required(),
                TextInput::make('longdescription')->required(),
                TextInput::make('tag')->required(),
                TextInput::make('deliverytime')->label('Delivery Time')->numeric()->required(),
                Select::make('category_id')
                    ->label('Category')
                    ->options(Category::all()->pluck('name', 'id')->toArray())
                    ->reactive()
                    ->afterStateUpdated(function (callable $set) {
                        $set('sub_category_id', null);
                    }),
                Select::make('sub_category_id')
                    ->label('Sub Category')
                    ->options(function (callable $get) {
                        $categoryId = $get('category_id');
                        if ($categoryId) {
                            return sub_category::where('category_id', $categoryId)->pluck('name', 'id');
                        }
                        return [];
                    }),
                Forms\Components\FileUpload::make('images')
                ->label('Images')
                ->multiple() // Make sure 'multiple' is enabled for multiple files
                ->imageEditor()
                ->directory('product-images') 
                ->required(),           
                Forms\Components\Repeater::make('details')
                ->label('Product Details')
                ->schema([
                    TextInput::make('color')
                        ->label('Color'),
                    Select::make('size')
                        ->label('Size')
                        ->multiple() // Allows multiple sizes
                        ->options([
                            'S' => 'Small',
                            'M' => 'Medium',
                            'L' => 'Large',
                            'XL' => 'Extra Large',
                        ]),
                    Forms\Components\FileUpload::make('image')
                        ->imageEditor()
                        ->label('Image')
                        ->directory('product-detail-images')
                        ->preserveFilenames()
                        ->nullable(),
                    TextInput::make('price')
                        ->label('Price')
                        ->numeric()
                        ->required(),
                    TextInput::make('discount')
                        ->label('Price After Discount')
                        ->numeric()
                        ->nullable(),
                    TextInput::make('stock')
                        ->label('Stock')
                        ->numeric(),
                        KeyValue::make('attributes'),
                ])
                ->defaultItems(1)
                ->required(),
            
                
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('tag'),               
                TextColumn::make('description'),
                ImageColumn::make('image')
                    ->label('Image')
                    ->getStateUsing(function ($record) {
                        return $record->images->first()->image_path ?? null;
                    }),
                TextColumn::make('category.name')->label('Category Name'),
                TextColumn::make('subCategory.name')->label('Sub Category Name'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    public static function getTranslatableLocales(): array
    {
        return ['en', 'ar'];
    }
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
