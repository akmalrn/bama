<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class OrderStep extends Model
{
    protected $table = 'order_steps';

    protected $fillable = [
        'title',
        'description',
        'image',
        'order'
    ];
}
