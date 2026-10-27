<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class ProductFeatureSection extends Model
{
    protected $guarded = [];

    protected $casts = ['status' => 'boolean'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function features()
    {
        return $this->hasMany(ProductFeature::class)->orderBy('order')->orderBy('id');
    }
}
