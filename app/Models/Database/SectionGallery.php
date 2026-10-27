<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class SectionGallery extends Model
{
    protected $table = 'section_gallery';

    protected $fillable = [
        'title',
        'subTitle',
        'description',
        'span',
    ];
}
