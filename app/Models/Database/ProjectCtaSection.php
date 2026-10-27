<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class ProjectCtaSection extends Model
{
    protected $table = 'project_cta_sections';

    protected $guarded = ['id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
