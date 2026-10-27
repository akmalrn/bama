<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CategoryPortofolio extends Model
{
    protected $table = 'categories_portofolio';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function portofolios()
    {
        return $this->hasMany(Portofolio::class, 'id_category');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug) && filled($category->name)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if (empty($category->slug) && filled($category->name)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
