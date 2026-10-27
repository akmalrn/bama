<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class ProjectWhyUsSection extends Model
{
    protected $table = 'project_why_us_sections';

    protected $guarded = ['id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function items()
    {
        return $this->hasMany(ProjectWhyUsItem::class)->orderBy('order');
    }
}
