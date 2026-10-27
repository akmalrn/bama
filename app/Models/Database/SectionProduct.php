<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class SectionProduct extends Model
{
    protected $table = 'section_product';

    protected $fillable = [
        'title',
        'subTitle',
        'description',
        'span',
        'image',
    ];
}
