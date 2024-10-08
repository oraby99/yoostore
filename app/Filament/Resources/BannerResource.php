<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Filament\Resources\BannerResource\RelationManagers;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BannerResource extends Resource
{
    use Translatable;
    protected static ?string $model = Banner::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Sections';
    protected static ?string $navigationLabel  = 'Sections';
    protected static ?string $navigationTitle = 'Sections';
    public static function getNavigationLabel(): string
    {
        return 'Sections';
    }
    public static function getPluralLabel(): string
    {
        return 'Sections';
    }
    public static function getLabel(): string
    {
        return 'Sections';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('tag')->required(),
                FileUpload::make('image')->imageEditor()->required()->label('Banner'),
                Forms\Components\TextInput::make('bannertag')->label('Banner Tag')->required(),
                FileUpload::make('bannerimage')->imageEditor()->label('Second Banner')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('tag'),
                Tables\Columns\ImageColumn::make('image')->label('Banner'),
                Tables\Columns\TextColumn::make('bannertag')->label('Banner Tag'),
                Tables\Columns\ImageColumn::make('bannerimage')->label('Second Banner'),
            ])
            ->filters([
                //
            ])
            ->actions([
                    Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    //Tables\Actions\DeleteAction::make()
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
