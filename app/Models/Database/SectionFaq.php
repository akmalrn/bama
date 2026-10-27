<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class SectionFaq extends Model
{
    protected $table = 'section_faq';

    protected $fillable = ['title','subTitle','description'];
}
