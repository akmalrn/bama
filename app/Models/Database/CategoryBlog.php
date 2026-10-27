<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CategoryBlog extends Model
{
    use HasFactory;

    protected $table = 'categoriesblog';

    protected $fillable = [
        'title',
    ];

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'id_category', 'id');
    }

    public function blog()
    {
        return $this->hasMany(Blog::class, 'id_category', 'id');
    }

    public function blogCount()
    {
        return $this->blogs()->count();
    }

    public function isEventPromotion(): bool
    {
        return in_array($this->slug, ['acara-promosi', 'acara-dan-promosi'], true)
            || in_array(Str::lower(trim($this->title)), ['acara & promosi', 'acara dan promosi'], true);
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
