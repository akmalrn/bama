<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'galleries';

    protected $fillable = [
        'title', 
        'image',
    ];

    
}
