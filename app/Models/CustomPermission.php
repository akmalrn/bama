<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomPermission extends SpatiePermission
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
}
