<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Profile extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded = [];
    protected $fillable =  ['tag'];
    public $translatable = ['tag'];
}
