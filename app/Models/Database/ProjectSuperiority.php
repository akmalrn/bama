<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class ProjectSuperiority extends Model
{
    protected $table = 'project_superiorities';

    protected $guarded = ['id'];

    public function section()
    {
        return $this->belongsTo(ProjectSuperioritySection::class, 'project_superiority_section_id');
    }
}
