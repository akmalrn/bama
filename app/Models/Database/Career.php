<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    use HasFactory;

    protected $table = 'careers';

    protected $fillable = [
        'hero_badge',
        'hero_title',
        'hero_description',
        'hero_image',
        'hero_button_text',
        'hero_button_link',
        'hero_button_2_text',
        'hero_button_2_link',
        'title',
        'subtitle',
        'image',
        'description',
        'meta_keywords',
        'meta_descriptions',
        'cta_title',
        'cta_description',
        'cta_button_text',
        'cta_button_link',
        'cta_button_icon',
    ];

    public function positions()
    {
        return $this->hasMany(CareerPosition::class, 'career_id')->orderBy('order')->orderBy('id');
    }
}
