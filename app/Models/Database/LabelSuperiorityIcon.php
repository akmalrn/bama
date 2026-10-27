<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class LabelSuperiorityIcon extends Model
{
    protected $table = 'label_superiority_icon';

    protected $fillable = [
        'title',
        'subTitle',        
        'description',
        'span',
        'image'
    ];
}
