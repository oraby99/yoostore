<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChatResource\Pages;
use App\Filament\Resources\ChatResource\RelationManagers;
use App\Models\Chat;
use Filament\Forms;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChatResource extends Resource
{
    protected static ?string $model = Chat::class;
    protected static ?string $navigationGroup = 'Chats';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                BelongsToSelect::make('user_id')
                    ->relationship('user', 'name') // Fetch user name via relationship
                    ->label('User')
                    ->disabled(), // Make it non-editable
                BelongsToSelect::make('product_id')
                    ->relationship('product', 'name->en') // Fetch product name via relationship
                    ->label('Product')
                    ->disabled(), // Make it non-editable
                Textarea::make('message')
                    ->label('Message')
                    ->disabled(),
                Textarea::make('reply')
                    ->label('Reply')
                    ->nullable(),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('user.name')->label('User'),
            TextColumn::make('product.name')->label('Product'),
            TextColumn::make('message')->label('Message'),
            TextColumn::make('reply')->label('Reply'),
            TextColumn::make('created_at')->label('Created At')->sortable(),
        ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChats::route('/'),
            'create' => Pages\CreateChat::route('/create'),
            'edit' => Pages\EditChat::route('/{record}/edit'),
        ];
    }
}
