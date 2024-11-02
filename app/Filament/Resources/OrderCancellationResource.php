<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderCancellationResource\Pages;
use App\Filament\Resources\OrderCancellationResource\RelationManagers;
use App\Models\OrderCancellation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderCancellationResource extends Resource
{
    protected static ?string $model = OrderCancellation::class;
    protected static ?string $navigationGroup = 'Orders'; 

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('order_id')
                    ->relationship('order', 'invoice_id')
                    ->required(),
                Forms\Components\TextInput::make('reason')
                    ->required(),
                Forms\Components\Textarea::make('comment')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.invoice_id')->label('Order Invoice ID'),
                Tables\Columns\TextColumn::make('reason'),
                Tables\Columns\TextColumn::make('comment'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOrderCancellations::route('/'),
            'create' => Pages\CreateOrderCancellation::route('/create'),
            //'edit' => Pages\EditOrderCancellation::route('/{record}/edit'),
        ];
    }
}
