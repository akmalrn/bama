<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class Gallerys extends Model
{
    protected $table = 'gallerys';

    protected $fillable = [
        'id_category',
        'title',
        'slug',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(CategoryGallerys::class, 'id_category');
    }
}
