<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Banner extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded = [];
    protected $fillable = ['name','image','tag','bannertag','bannerimage'];
    public $translatable = ['name','tag','bannertag'];
    public function products()
    {
        return $this->hasMany(Product::class, 'tag', 'tag');
    }
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($category) {
            $baseUrl = 'https://amhere.net/storage/';
            $category->image = $category->image ? $baseUrl . ltrim($category->image, '/') : null;
            $category->bannerimage = $category->bannerimage ? $baseUrl . ltrim($category->bannerimage, '/') : null;
        });
    }
}
