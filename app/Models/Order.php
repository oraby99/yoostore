<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'invoice_id', 'total_price', 'order_status_id', 'payment_method', 'payment_status_id', 'address_id'
    ];
    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }
    public function paymentStatus()
    {
        return $this->belongsTo(PaymentStatus::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    public function products()
    {
        return $this->belongsToMany(ImportedProduct::class, 'order_product');
    }
    public function productDetails()
    {
        return $this->hasMany(ProductDetail::class, 'product_id');
    }
    public function statusChanges()
    {
        return $this->hasMany(OrderStatusChange::class);
    }
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }
}

