<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Http\Controllers\Api\Payment\FatoorahController;
use App\Models\Address;
use App\Models\Notification;
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
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()->disabled(),
                Forms\Components\TextInput::make('invoice_id')->required()->disabled(),
                Forms\Components\TextInput::make('total_price')->numeric()->required()->disabled(),
                Select::make('payment_status_id')
                    ->label('Payment Status')
                    ->relationship('paymentStatus', 'name')
                    ->required()
                    ->afterStateUpdated(function ($state, $get) {
                        $orderId = $get('id');
                        OrderStatusChange::create([
                            'order_id' => $orderId,
                            'status' => 'Payment ' . $state,
                        ]);
                        self::notifyUser($orderId, $state, 'Payment');
                    }),
                Select::make('order_status_id')
                    ->label('Order Status')
                    ->relationship('orderStatus', 'name')
                    ->required()
                    ->afterStateUpdated(function ($state, $get) {
                        $orderId = $get('id');
                        OrderStatusChange::create([
                            'order_id' => $orderId,
                            'status' => $state,
                        ]);
                        self::notifyUser($orderId, $state, 'Order');
                    }),
            ]);
    }

    protected static function notifyUser($orderId, $status, $type)
    {
        $order = Order::find($orderId);
        $user = $order->user;
        if ($user && $user->device_token) {
            $title = $type . ' Status Updated';
            $message = "Your $type #" . $order->id . " status is now: " . $status;
            $data = [
                "registration_ids" => [$user->device_token],
                "notification" => [
                    "title" => $title,
                    "body" => $message,
                ],
                "data" => [
                    "order_id" => (string)$order->id,
                    "type"     => $type,
                    "status"   => $status,
                ]
            ];
            $response = FatoorahController::sendFCMNotification($data, 'yoo-store-ed4ba-de6f28257b6d.json');
            Notification::create([
                'user_id'  => $user->id,
                'order_id' => $order->id,
                'message'  => $message,
                'type'     => $type,
            ]);
            if (isset($response['error']) && !empty($response['error'])) {
                \Log::error('FCM Error: ' . json_encode($response['error']));
            }
        }
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
                        $productDetails = $record->orderProducts->map(function ($orderProduct) {
                            $product = $orderProduct->product;
                            $productDetail = $orderProduct->productDetail;
                            return [
                                'name' => $product->name,
                                'quantity' => $orderProduct->quantity,
                                'size' => $orderProduct->size,
                                'price' => $productDetail->price ?? $productDetail->typeprice,
                                'stock' => $productDetail->stock ?? $productDetail->typestock,
                                'color' => $productDetail->color ?? $productDetail->typename,
                            ];
                        });
                        $html = '<table style="width:100%; border-collapse: collapse;">';
                        $html .= '<thead><tr><th style="border: 1px solid #ddd; padding: 8px;">
                        Product</th><th style="border: 1px solid #ddd; padding: 8px;">
                        Qty</th><th style="border: 1px solid #ddd; padding: 8px;">Size</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Price</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Stock</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Color/Name</th></tr>
                        </thead>';
                        $html .= '<tbody>';
                        foreach ($productDetails as $detail) {
                            $html .= '<tr>';
                            $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . $detail['name'] . '</td>';
                            $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . $detail['quantity'] . '</td>';
                            $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . $detail['size'] . '</td>';
                            $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . $detail['price'] . '</td>';
                            $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . $detail['stock'] . '</td>';
                            $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . $detail['color'] . '</td>';
                            $html .= '</tr>';
                        }
                        $html .= '</tbody></table>';
                        return $html;
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
