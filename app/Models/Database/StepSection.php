<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class StepSection extends Model
{
    protected $table = 'step_section';

    protected $fillable = [
        'title',
        'subTitle',
        'description',      
    ];
}
