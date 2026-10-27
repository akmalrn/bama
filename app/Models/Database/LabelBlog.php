<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class LabelBlog extends Model
{
    protected $table = 'label_blog';

    protected $fillable = [
        'title',
        'subTitle',
        'description',
        'span'
    ];
}
