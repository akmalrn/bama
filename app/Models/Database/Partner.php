<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partner extends Model
{
    protected $table = 'partners';

    protected $guarded = ['id'];

    protected $casts = [
        'is_slider' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryPartner::class, 'id_category');
    }
}
