<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded = [];
    protected $fillable = ['name', 'description', 'tag', 'longdescription', 'discount', 'attributes', 'deliverytime', 'category_id', 'sub_category_id', 'is_published', 'in_stock'];
    public $translatable = ['name', 'description', 'tag', 'longdescription',];
    public function typeDetails()
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
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'product_id');
    }
    public function rates()
    {
        return $this->hasMany(Rate::class);
    }
    public function orders()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function productDetails()
    {
        return $this->hasMany(ProductDetail::class);
    }
    protected $casts = [
        'is_published' => 'boolean',
        'in_stock' => 'boolean',
        'attributes' => 'array',
    ];
    
}

