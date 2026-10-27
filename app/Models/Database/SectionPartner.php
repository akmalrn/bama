<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class SectionPartner extends Model
{
    protected $table = 'section_partner';

    protected $fillable = [
        'title',
        'span',
        'subTitle',
        'description',
    ];
}
