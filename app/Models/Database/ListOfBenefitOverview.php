<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class ListOfBenefitOverview extends Model
{
    protected $table = 'list_of_benefits_overview';

    protected $fillable = [
        'icon',
        'image',
        'title',
        'description',
    ];
}
