<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contact';

    protected $guarded = ['id'];
}
