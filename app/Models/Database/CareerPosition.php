<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class CareerPosition extends Model
{
    use HasFactory;

    protected $table = 'career_positions';

    protected $fillable = [
        'career_id',
        'title',
        'slug',
        'department',
        'type',
        'location',
        'description',
        'requirements',
        'salary_range',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($position) {
            if (empty($position->slug)) {
                $position->slug = Str::slug($position->title);
            }
        });
    }

    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class, 'career_id');
    }
}
