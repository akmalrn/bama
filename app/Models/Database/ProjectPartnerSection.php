<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class ProjectPartnerSection extends Model
{
    protected $table = 'project_partner_sections';

    protected $guarded = ['id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function partners()
    {
        return $this->hasMany(ProjectPartner::class)->orderBy('order');
    }
}
