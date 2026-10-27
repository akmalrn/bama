<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BusinessField extends Model
{
    protected $table = 'business_fields';

    protected $fillable = [
        'slug',
        'title',
        'overview',
        'description',
        'icon',
        'image',
        'meta_keywords',
        'meta_descriptions',
        'status',
        'order',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected $appends = [
        'color',
    ];

    public function getColorAttribute(): string
    {
        $map = [
            'retail' => 'purple',
            'rental' => 'red',
            'trading' => 'purple',
            'jasa-profesional' => 'blue',
            'real-estate' => 'cyan',
            'finance' => 'red',
            'logistic' => 'orange',
            'manufaktur' => 'cyan',
            'food-beverage' => 'orange',
            'food-and-beverage' => 'orange',
            'pemerintahan' => 'purple',
            'konstruksi' => 'orange',
            'pendidikan' => 'cyan',
            'engineering' => 'blue',
            'project-based' => 'red',
            'mining' => 'blue',
            'hospitality' => 'red',
        ];

        return $map[$this->slug] ?? ($this->attributes['color'] ?? 'blue');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            if (empty($item->slug)) {
                $item->slug = Str::slug($item->title);
            }
        });

        static::updating(function ($item) {
            $item->slug = Str::slug($item->title);
        });
    }
}
