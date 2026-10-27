<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class ServiceSection extends Model
{
    protected $table = 'services_section';

    protected $fillable = [
        'title',
        'subTitle',
        'description',
        'span',
        'image',
        'title_cta',
        'description_cta',
        'subTitle_cta',
        'name_link',
        'link',
    ];
}
