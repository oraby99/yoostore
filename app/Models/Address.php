<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'name', 'phone', 'street', 'landmark', 'area', 'country',
        'flat_no', 'address_type', 'is_default'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
