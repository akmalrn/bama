<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CategoryGallerys extends Model
{
    protected $table = 'categories_gallerys';

    protected $fillable = ['title', 'slug'];

    public function gallerys()
    {
        return $this->hasMany(Gallerys::class, 'id_category');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = Str::slug($category->title);
        });

        static::updating(function ($category) {
            $category->slug = Str::slug($category->title);
        });
    }
}
