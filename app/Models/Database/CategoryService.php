<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CategoryService extends Model
{
    protected $table = 'categories_service';

    protected $fillable = [
        'slug',
        'title',
    ];

    public function services()
    {
        return $this->hasMany(Service::class, 'id_category')->orderBy('order', 'asc')->orderBy('id', 'asc');
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
