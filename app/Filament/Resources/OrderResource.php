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
use App\Models\OrderStatus;
use App\Models\PaymentStatus;
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
                    ->required()->disabled(),
    
                Forms\Components\TextInput::make('invoice_id')->required()->disabled(),
                Forms\Components\TextInput::make('total_price')->numeric()->required()->disabled(),
    
                Forms\Components\Select::make('payment_status_id')
                    ->label('Payment Status')
                    ->relationship('paymentStatus', 'name')
                    ->required(),
    
                Forms\Components\Select::make('order_status_id')
                    ->label('Order Status')
                    ->relationship('orderStatus', 'name')
                    ->required()
                    ->afterStateUpdated(function (callable $set, $state, $get) {
                        OrderStatusChange::create([
                            'order_id' => $get('id'),
                            'status' => $state,
                        ]);
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
                Tables\Columns\TextColumn::make('paymentStatus.name')->label('Payment Status'),
                Tables\Columns\TextColumn::make('orderStatus.name')->label('Order Status'),
                Tables\Columns\TextColumn::make('address.street')->label('Address'),
                Tables\Columns\TextColumn::make('created_at')->label('Order Date')->dateTime(),
                Tables\Columns\TextColumn::make('products')->label('Product Details')
                    ->formatStateUsing(function ($record) {
                        return $record->orderProducts->map(function ($orderProduct) {
                            $product = $orderProduct->product;
                            $productDetail = $orderProduct->productDetail;
                            return $product->name . ' - Qty: ' . $orderProduct->quantity . ', Size: ' . $orderProduct->size . 
                                '<br>Price: ' . ($productDetail->price ?? $productDetail->typeprice) . 
                                '<br>Stock: ' . ($productDetail->stock ?? $productDetail->typestock) . 
                                '<br>Name/Color: ' . ($productDetail->color ?? $productDetail->typename) ;
                        })->implode('<hr>');
                    })->html(),
            ])->defaultSort('created_at', 'desc') 
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('downloadPdf')
                    ->label('Download Invoice')
                    ->url(fn ($record) => route('orders.pdf', $record))
                    ->openUrlInNewTab(),
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
