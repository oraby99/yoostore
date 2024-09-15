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
use Filament\Forms\Components\Toggle;
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
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('description')->required(),
                TextInput::make('longdescription')->required()->label('long description'),
                TextInput::make('tag')->required(),
                TextInput::make('deliverytime')->label('Delivery Time')->numeric()->required(),
                KeyValue::make('attributes')->required(),
                TextInput::make('discount')->label('discount')->numeric(),
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
                    ->multiple()
                    ->imageEditor()
                    ->directory('product-images') 
                    ->required(),
                    
                Toggle::make('is_product_details')
                    ->label('Use Product Details')
                    ->default(true)
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        // Clear the other repeater fields based on the toggle state
                        if ($state) {
                            $set('type_details', []);
                        } else {
                            $set('product_details', []);
                        }
                    }),
    
                Forms\Components\Repeater::make('product_details')
                    ->label('Product Details')
                    ->visible(fn (callable $get) => $get('is_product_details'))
                    ->schema([
                        TextInput::make('color')->label('Color')->required(),
                        Select::make('size')->required()
                            ->label('Size')
                            ->multiple()
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
                            ->required(),
                        TextInput::make('price')
                            ->label('Price')
                            ->numeric()->required(),
                        TextInput::make('stock')
                            ->label('Stock')
                            ->numeric()->required(),
                    ])
                    ->defaultItems(1),
    
                Forms\Components\Repeater::make('type_details')
                    ->label('Product Type Details')
                    ->visible(fn (callable $get) => !$get('is_product_details'))
                    ->schema([
                        TextInput::make('typename')
                            ->label('Type Name')->required(),
                        Forms\Components\FileUpload::make('typeimage')
                            ->imageEditor()
                            ->label('Type Image')
                            ->directory('product-detail-images')
                            ->preserveFilenames()
                            ->required(),
                        TextInput::make('typeprice')
                            ->label('Type Price')
                            ->numeric()->required(),
                        TextInput::make('typestock')
                            ->label('Type Stock')
                            ->numeric()->required(),
                    ])
                    ->defaultItems(1),
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
