<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CategoryPartner extends Model
{
    protected $table = 'categories_partner';

    protected $fillable = [
        'name',
        'slug',
        'title',
    ];

    public function partners(): HasMany
    {
        return $this->hasMany(Partner::class, 'id_category');
    }

    public function isSystemCategory(): bool
    {
        $systemSlugs = ['partner', 'mitra-pihat-ketiga', 'mitra-pihak-ketiga', 'teknologi'];
        $normalizedTitle = Str::slug($this->title ?? '');
        $normalizedName = Str::slug($this->name ?? '');

        return in_array($this->slug, $systemSlugs, true)
            || in_array($normalizedTitle, $systemSlugs, true)
            || in_array($normalizedName, $systemSlugs, true)
            || in_array($this->id, [1, 2, 3, 4], true);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $base = $category->name ?: $category->title;
            $category->slug = Str::slug($base);
            if (empty($category->title)) {
                $category->title = $category->name;
            }
        });

        static::updating(function ($category) {
            $base = $category->name ?: $category->title;
            $category->slug = Str::slug($base);
            if (empty($category->title)) {
                $category->title = $category->name;
            }
        });
    }
}
