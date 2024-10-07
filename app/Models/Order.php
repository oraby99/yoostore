<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'invoice_id', 'total_price', 'status', 'payment_method', 'payment_status', 'address_id'
    ];
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
        return $this->belongsToMany(Product::class, 'order_product')
            ->withPivot('product_detail_id', 'quantity', 'size')
            ->with('productDetails'); // Load product details only
    }
    public function productDetails()
    {
        return $this->hasMany(ProductDetail::class, 'product_id');
    }
    public function statusChanges()
{
    return $this->hasMany(OrderStatusChange::class);
}

}

