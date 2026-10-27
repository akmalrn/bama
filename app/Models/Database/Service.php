<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    protected $table = 'services';

    protected $fillable = [
        'slug',
        'title',
        'overview',
        'description',
        'icon',
        'image',
        'hero_badge',
        'hero_span',
        'hero_title',
        'hero_description',
        'hero_image',
        'meta_keywords',
        'meta_descriptions',
        'status',
        'id_category',
        'button_name',
        'button_url',
        'is_featured',
        'images',
        'projects_data',
        'projects_badge',
        'projects_title',
        'show_projects',
        'workflow_data',
        'workflow_title',
        'workflow_subtitle',
        'show_workflow',
        'portfolios_data',
        'portfolios_badge',
        'portfolios_title',
        'show_portfolios',
        'order',
    ];

    protected $casts = [
        'images' => 'array',
        'projects_data' => 'array',
        'workflow_data' => 'array',
        'portfolios_data' => 'array',
        'show_projects' => 'boolean',
        'show_workflow' => 'boolean',
        'show_portfolios' => 'boolean',
        'is_featured' => 'boolean',
        'order' => 'integer',
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

    public function category()
    {
        return $this->belongsTo(CategoryService::class, 'id_category');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->title);
            }
        });

        static::updating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->title);
            }
        });
    }
}
