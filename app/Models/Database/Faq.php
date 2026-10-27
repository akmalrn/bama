<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = 'faqs';

    protected $fillable = ['question', 'answer', 'order'];

    protected $casts = [
        'order' => 'integer',
    ];
}
