<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'purchase_orders';

    public const STATUS_DRAFT = 'draft';

    public const STATUS_ISSUED = 'issued';

    public const STATUS_ACKNOWLEDGED = 'acknowledged';

    public const STATUS_IN_DELIVERY = 'in_delivery';

    public const STATUS_PARTIALLY_RECEIVED = 'partially_received';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    public const PAYMENT_TERMS = [
        'Net 30 Days',
        'Net 14 Days',
        'Net 60 Days',
        'COD (Cash on Delivery)',
        '50% DP, 50% On Delivery',
        '100% Advance Payment',
    ];

    protected $fillable = [
        'po_number',
        'purchase_request_id',
        'vendor_id',
        'project_id',
        'po_date',
        'delivery_deadline',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'discount_amount',
        'grand_total',
        'payment_terms',
        'delivery_address',
        'status',
        'terms_conditions',
        'attachment_path',
        'issued_by',
        'notes',
    ];

    protected $casts = [
        'po_date' => 'date',
        'delivery_deadline' => 'date',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    public function purchaseRequest(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(ProcurementVendor::class, 'vendor_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function issuedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id');
    }

    public function logisticsDeliveries(): HasMany
    {
        return $this->hasMany(LogisticsDelivery::class, 'purchase_order_id');
    }
}
