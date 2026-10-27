<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Portofolio extends Model
{
    protected $table = 'portofolios';

    protected $fillable = [
        'id_category',
        'name',
        'slug',
        'year',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(CategoryPortofolio::class, 'id_category');
    }

    public function getFirstImageAttribute()
    {
        $images = is_array($this->images) ? $this->images : json_decode($this->images ?? '[]', true);
        return $images[0] ?? null;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($portofolio) {
            $portofolio->slug = Str::slug($portofolio->name);
        });

        static::updating(function ($portofolio) {
            $portofolio->slug = Str::slug($portofolio->name);
        });
    }
}
