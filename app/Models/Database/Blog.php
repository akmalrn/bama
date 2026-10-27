<?php

namespace App\Models\Database;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blogs';

    protected $fillable = [
        'id_category',
        'title',
        'description',
        'image',
        'overview',
        'meta_keywords',
        'meta_descriptions',
        'user_id',
        'published_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categoryBlog()
    {
        return $this->belongsTo(CategoryBlog::class, 'id_category', 'id');
    }

    public function getFrontendUrlAttribute(): string
    {
        return ($this->categoryBlog?->isEventPromotion() && \Illuminate\Support\Facades\Route::has('events-promotions.show'))
            ? route('events-promotions.show', $this->slug)
            : route('blog.show', $this->slug);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            $blog->slug = Str::slug($blog->title);
        });

        static::updating(function ($blog) {
            $blog->slug = Str::slug($blog->title);
        });
    }
    public function getFormattedDateAttribute()
    {
        return $this->date ? $this->date->format('F j, Y') : '-';
    }
    protected $casts = [
        'published_at' => 'datetime',
    ];
}
