<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class ProductFeature extends Model
{
    protected $guarded = [];

    protected $casts = ['status' => 'boolean'];

    public function section()
    {
        return $this->belongsTo(ProductFeatureSection::class, 'product_feature_section_id');
    }
}
