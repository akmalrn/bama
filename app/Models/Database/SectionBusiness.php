<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class SectionBusiness extends Model
{
    protected $table = "section_business";

    protected $fillable = [
        'title',
        'description',
    ];
}
