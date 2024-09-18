<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Offer extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded = [];
    protected $fillable = ['secondbanner', 'banner','tag','bannertag'];
    public $translatable = ['bannertag','tag'];
}
