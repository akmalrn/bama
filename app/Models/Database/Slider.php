<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = 'sliders';

    protected $fillable = [
        'title',
        'sub_title',
        'description',
        'image',
        'name_link',
        'link',
        'name_link_2',
        'link_2'
    ];
}
