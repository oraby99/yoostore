<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;
    protected $fillable = [
        'rate',
        'title',
        'description',
        'images',
        'product_id',
        'user_id',
    ];
    protected $casts = [
        'images' => 'array',
    ];
    public function product()
    {
        return $this->belongsTo(ImportedProduct::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
