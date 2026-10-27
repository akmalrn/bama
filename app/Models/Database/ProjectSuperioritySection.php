<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class ProjectSuperioritySection extends Model
{
    protected $table = 'project_superiority_sections';

    protected $guarded = ['id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function superiorities()
    {
        return $this->hasMany(ProjectSuperiority::class)->orderBy('order');
    }
}
