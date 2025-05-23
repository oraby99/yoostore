<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'product_id', 'message', 'reply'];
    public function product()
    {
        return $this->belongsTo(ImportedProduct::class, 'product_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
