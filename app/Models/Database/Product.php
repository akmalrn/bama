<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'id_category',
        'slug',
        'title',
        'subtitle',
        'highlight',
        'overview',
        'specifications',
        'benefits',
        'applications',
        'description',
        'button_name',
        'button_url',
        'icon',
        'image',
        'banner_image',
        'thumbnail',
        'meta_keywords',
        'meta_title',
        'meta_descriptions',
        'og_image',
        'is_featured',
        'status',
        'images',
        'order',
    ];

    protected $casts = [
        'images' => 'array',
        'is_featured' => 'boolean',
        'status' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryProduct::class, 'id_category');
    }

    public function featureSection(): HasOne
    {
        return $this->hasOne(ProductFeatureSection::class);
    }

    public function ecosystemSection(): HasOne
    {
        return $this->hasOne(ProductEcosystemSection::class);
    }

    public function solutionSection(): HasOne
    {
        return $this->hasOne(ProductSolutionSection::class);
    }

    public function productOverview(): HasOne
    {
        return $this->hasOne(ProductOverview::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(ProductFaq::class)->orderBy('order')->orderBy('id');
    }

    protected static function booted(): void
    {
        static::saving(function (Product $product): void {
            if ($product->isDirty('title') || empty($product->slug)) {
                $product->slug = static::generateUniqueSlug($product->title, $product->id);
            }
        });
    }

    public static function generateUniqueSlug(?string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug((string) $title);
        if ($baseSlug === '') {
            $baseSlug = 'produk';
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
