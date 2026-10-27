<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class SuperiorityIcon extends Model
{
    protected $table = 'superioritys_icon';

    protected $fillable = [
        'icon',
        'title',
        'description',        
    ];
}
