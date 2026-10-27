<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class ProjectPartner extends Model
{
    protected $table = 'project_partners';

    protected $guarded = ['id'];

    public function section()
    {
        return $this->belongsTo(ProjectPartnerSection::class, 'project_partner_section_id');
    }
}
