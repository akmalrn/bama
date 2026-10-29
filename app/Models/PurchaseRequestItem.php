<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseRequestItem extends Model
{
    use HasFactory;

    protected $table = 'purchase_request_items';

    protected $fillable = [
        'purchase_request_id',
        'item_name',
        'specifications',
        'qty',
        'unit',
        'estimated_unit_price',
        'estimated_total_price',
        'notes',
    ];

    protected $casts = [
        'qty' => 'decimal:2',
        'estimated_unit_price' => 'decimal:2',
        'estimated_total_price' => 'decimal:2',
    ];

    public function purchaseRequest(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_id');
    }
}
