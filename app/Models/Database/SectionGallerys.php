<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class SectionGallerys extends Model
{
    protected $table = 'section_gallerys';

    protected $fillable = ['title', 'subTitle', 'description', 'span']; 
}
