<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class LabelSlider extends Model
{
    protected $table = 'label_slider';

    protected $fillable = [
        'title',
        'description',
        'name_link',
        'link',
        'name_link_2',
        'link_2',
    ];
}
