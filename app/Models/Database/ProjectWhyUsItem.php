<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class ProjectWhyUsItem extends Model
{
    protected $table = 'project_why_us_items';

    protected $guarded = ['id'];

    public function section()
    {
        return $this->belongsTo(ProjectWhyUsSection::class, 'project_why_us_section_id');
    }
}
