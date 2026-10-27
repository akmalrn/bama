<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class ProjectFeatureSection extends Model
{
    protected $table = 'project_feature_sections';

    protected $guarded = ['id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function features()
    {
        return $this->hasMany(ProjectFeature::class)->orderBy('order');
    }
}
