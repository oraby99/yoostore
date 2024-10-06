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
        return $this->hasMany(OrderProduct::class);
    }
}

