<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded = [];
    protected $fillable = ['name', 'image','banner','tag','bannerimage'];
    public $translatable = ['name','tag'];
    public function subcategories()
    {
        return $this->hasMany(sub_category::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($category) {
            $baseUrl = 'https://amhere.net/storage/';
            $category->image = $category->image ? $baseUrl . ltrim($category->image, '/') : null;
            $category->banner = $category->banner ? $baseUrl . ltrim($category->banner, '/') : null;
            $category->bannerimage = $category->bannerimage ? $baseUrl . ltrim($category->bannerimage, '/') : null;
        });
    }
}
