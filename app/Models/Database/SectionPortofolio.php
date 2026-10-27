<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class SectionPortofolio extends Model
{
    protected $table = 'section_portofolio';

    protected $fillable = [
        'title',
        'span',
        'subTitle',
        'description',
        'image',
        'project_tag',
        'project_title',
    ];
}
