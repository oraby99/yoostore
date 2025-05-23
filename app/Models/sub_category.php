<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class sub_category extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded = [];
    protected $fillable = ['name', 'image','banner','category_id','bannertag','bannerimage'];

    public $translatable = ['name','bannertag'];
    public function category()
    {
        return $this->belongsTo(Category::class);
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
