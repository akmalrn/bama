<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class Overview extends Model
{
    protected $table = 'overview';

    protected $fillable = [
        'title',
        'subTitle',
        'description',
        'image',
        'overview',
        'note',
        'name_link',
        'link'
    ];
}
