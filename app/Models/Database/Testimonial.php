<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $table = 'testimonials';

    protected $fillable = [
        'title',
        'name',
        'description',
        'image',
        'foto',
        'logo_image',
        'position',
        'address',
        'star',
    ];

    public function getDisplayNameAttribute(): string
    {
        return $this->title ?: ($this->name ?: '');
    }

    public function getDisplayImageAttribute(): ?string
    {
        return $this->image ?: $this->foto;
    }
}
