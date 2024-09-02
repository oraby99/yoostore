<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded = [];
    protected $fillable = ['name', 'image','banner','tag'];

    public $translatable = ['name','tag'];
    public function subcategories()
    {
        return $this->hasMany(sub_category::class);
    }
}
