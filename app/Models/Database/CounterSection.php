<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class CounterSection extends Model
{
    protected $table = 'counter_section';

    protected $fillable = [
        'title',
        'value',
        'suffix',
        'image',
        'order',
        'icon'
    ];

    protected $appends = ['name'];

    public function getNameAttribute()
    {
        return $this->attributes['title'] ?? null;
    }
}
