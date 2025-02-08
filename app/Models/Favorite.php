<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'is_favorite'];

    public function product()
    {
        return $this->belongsTo(ImportedProduct::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
