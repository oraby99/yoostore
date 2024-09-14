<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;
    protected $table = 'product_details';
    protected $fillable = [
        'product_id', 'price', 'stock', 'image', 'color', 'size',
        'typename', 'typeprice', 'typestock', 'typeimage'
    ];
    protected $casts = [
        'size' => 'array',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
