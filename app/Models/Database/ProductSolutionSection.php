<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class ProductSolutionSection extends Model
{
    protected $guarded = [];

    protected $casts = ['status' => 'boolean'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function solutions()
    {
        return $this->hasMany(ProductSolution::class)->orderBy('order')->orderBy('id');
    }
}
