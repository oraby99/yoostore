<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderStatusChange;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Orders'; 
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('invoice_id')
                    ->required(),
                Forms\Components\TextInput::make('total_price')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('payment_status')
                    ->options([
                        'Pending'   => 'Pending',
                        'Paid'      => 'Paid',
                        'Cancelled' => 'Cancelled',
                    ])
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'Received'   => 'Received',
                        'Cancelled'  => 'Cancelled',
                        'Delivered'  => 'Delivered',
                    ])
                    ->required()
                    ->afterStateUpdated(function (callable $set, $state, $get) {
                        $orderId = $get('id'); // Get the order ID
                        $originalStatus = $get('status'); // Get the original status

                        // Check if the status is changed
                       // if ($state !== $originalStatus) {
                            // Store the status change in the OrderStatusChange model
                            OrderStatusChange::create([
                                'order_id' => $orderId,
                                'status'   => $state,
                            ]);
                       // }
                    }),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('user.name')->label('User Name'),
            Tables\Columns\TextColumn::make('invoice_id')->label('Invoice ID'),
            Tables\Columns\TextColumn::make('total_price')->label('Total Price'),
            Tables\Columns\TextColumn::make('payment_status')->label('payment_status'),
            Tables\Columns\TextColumn::make('status')->label('Order Status'),
            Tables\Columns\TextColumn::make('address.street')->label('Address'),
            Tables\Columns\TextColumn::make('created_at')->label('Order Date')->dateTime(),
        ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
