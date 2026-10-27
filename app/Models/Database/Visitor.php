<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'country',
        'province',
        'city',
        'isp',
        'url',
    ];
}
