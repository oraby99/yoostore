<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;
    protected $table = 'product_details';
    protected $fillable = ['product_id', 'price', 'discount', 'stock', 'image', 'color', 'size', 'attributes'];
    protected $casts = [
        'attributes' => 'array',
        'size' => 'array',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
