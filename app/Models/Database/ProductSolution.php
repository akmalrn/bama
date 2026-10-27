<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class ProductSolution extends Model
{
    protected $guarded = [];

    protected $casts = ['status' => 'boolean'];

    public function section()
    {
        return $this->belongsTo(ProductSolutionSection::class, 'product_solution_section_id');
    }
}
