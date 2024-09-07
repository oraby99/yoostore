<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded = [];
    public $translatable = ['name', 'description', 'tag'];
    public function productDetails()
    {
        return $this->hasMany(ProductDetail::class, 'product_id');
    }
    public function subCategory()
    {
        return $this->belongsTo(sub_category::class, 'sub_category_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
}
