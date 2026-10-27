<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'teams';

    public $fillable = [
        'name',
        'position',
        'image',
        'description'
    ];
}
