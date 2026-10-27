<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class Superiority extends Model
{
    protected $table = 'superioritys';

    protected $fillable = [
        'title',
        'description',
        'quality_title',
        'quality_items',
        'image',
        'logo_image',
    ];

    protected function casts(): array
    {
        return [
            'quality_items' => 'array',
        ];
    }
}
