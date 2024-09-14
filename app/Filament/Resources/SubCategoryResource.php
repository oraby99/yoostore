<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubCategoryResource\Pages;
use App\Filament\Resources\SubCategoryResource\RelationManagers;
use App\Models\Category;
use App\Models\sub_category;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubCategoryResource extends Resource
{
    use Translatable;
    protected static ?string $model = sub_category::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Categories';
    protected static ?string $navigationLabel = 'Sub Category';


    public static function form(Form $form): Form
    {
        $categories = Category::all()->pluck('name', 'id');
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                FileUpload::make('image')->imageEditor()->required(),
                Forms\Components\Select::make('category_id')->options($categories)->required(),
                FileUpload::make('banner')->imageEditor()->required(),
                FileUpload::make('bannerimage')->imageEditor()->required()->label('Second Banner'),
                Forms\Components\TextInput::make('bannertag')->required(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('category.name')->label('Category Name'),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\ImageColumn::make('banner'),
                Tables\Columns\ImageColumn::make('bannerimage')->label('Second Banner'),
                Tables\Columns\TextColumn::make('bannertag'),

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
            'index' => Pages\ListSubCategories::route('/'),
            'create' => Pages\CreateSubCategory::route('/create'),
            'edit' => Pages\EditSubCategory::route('/{record}/edit'),
        ];
    }
}
