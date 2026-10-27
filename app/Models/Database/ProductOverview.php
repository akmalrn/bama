<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class ProductOverview extends Model
{
    protected $guarded = [];

    protected $casts = ['status' => 'boolean'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
