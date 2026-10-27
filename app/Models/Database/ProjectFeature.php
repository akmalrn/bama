<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class ProjectFeature extends Model
{
    protected $table = 'project_features';

    protected $guarded = ['id'];

    protected $casts = [
        'bullet_points' => 'array',
    ];

    public function section()
    {
        return $this->belongsTo(ProjectFeatureSection::class, 'project_feature_section_id');
    }
}
