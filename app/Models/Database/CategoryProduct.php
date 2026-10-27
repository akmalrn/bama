<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class CategoryProduct extends Model
{
    protected $table = 'categories_product';

    protected $fillable = ['name', 'slug', 'title', 'overview', 'description', 'icon', 'image', 'order', 'status'];

    protected $casts = ['status' => 'boolean'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'id_category');
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id_category');
    }

    protected static function booted(): void
    {
        static::saving(function (CategoryProduct $category): void {
            if ($category->isDirty(['name', 'title']) || empty($category->slug)) {
                $category->slug = static::generateUniqueSlug($category->name ?: $category->title, $category->id);
            }
        });
    }

    public static function generateUniqueSlug(?string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug((string) $title);
        if ($baseSlug === '') {
            $baseSlug = 'kategori-produk';
        }

        $slug = $baseSlug;
        $counter = 1;

        while (static::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
