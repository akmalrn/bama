<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class ListOfBenefitAboutUs extends Model
{
    protected $table = 'list_of_benefits_about_us';

    protected $fillable = [
        'image',
        'title',
        'description',
    ];
}
