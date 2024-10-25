<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChatResource\Pages;
use App\Models\Chat;
use Filament\Forms;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ChatResource extends Resource
{
    protected static ?string $model = Chat::class;
    protected static ?string $navigationGroup = 'Chats';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string $view = 'vendor.filament.components.chats';
    public function mount(): void
    {
        $this->chats = Chat::with(['user', 'product'])->get();
    }
    protected function getViewData(): array
    {
        return [
            'chats' => $this->chats,
        ];
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                BelongsToSelect::make('user_id')
                    ->relationship('user', 'name')
                    ->label('User')
                    ->disabled(),
                BelongsToSelect::make('product_id')
                    ->relationship('product', 'name->en')
                    ->label('Product')
                    ->disabled(),
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
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('product.name')
                    ->label('Product')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('message')
                    ->label('Message')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->message),
                TextColumn::make('reply')
                    ->label('Reply')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->reply),
                TextColumn::make('created_at')
                    ->label('Sent At')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\CustomChatView::route('/'),
            'create' => Pages\CreateChat::route('/create'),
            'edit' => Pages\EditChat::route('/{record}/edit'),
        ];
    }
    
}
