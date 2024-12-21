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
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($category) {
            $baseUrl = 'https://amhere.net/storage/';
            $category->banner = $category->banner ? $baseUrl . ltrim($category->banner, '/') : null;
            $category->secondbanner = $category->secondbanner ? $baseUrl . ltrim($category->secondbanner, '/') : null;
        });
    }
}
