<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    protected $table = 'workshops';

    protected $fillable = [
        'title',
        'location',
        'map',
    ];
}
