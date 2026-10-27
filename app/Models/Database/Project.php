<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $table = 'projects';

    protected $guarded = ['id'];

    protected $casts = [
        'images' => 'array',
        'status' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(CategoryProject::class, 'id_category');
    }

    public function partnerSection()
    {
        return $this->hasOne(ProjectPartnerSection::class);
    }

    public function superioritySection()
    {
        return $this->hasOne(ProjectSuperioritySection::class);
    }

    public function featureSection()
    {
        return $this->hasOne(ProjectFeatureSection::class);
    }

    public function whyUsSection()
    {
        return $this->hasOne(ProjectWhyUsSection::class);
    }

    public function ctaSection()
    {
        return $this->hasOne(ProjectCtaSection::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->slug) && filled($project->title)) {
                $project->slug = Str::slug($project->title);
            }
        });

        static::updating(function ($project) {
            if (empty($project->slug) && filled($project->title)) {
                $project->slug = Str::slug($project->title);
            }
        });
    }
}
