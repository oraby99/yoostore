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

}
