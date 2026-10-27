<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class LabelSuperiority extends Model
{
    protected $table = 'label_superiority';

    protected $fillable = [
        'title',
        'subTitle',
        'description',
        'span'
    ];
}
