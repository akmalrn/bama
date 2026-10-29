<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogisticsDelivery extends Model
{
    use HasFactory;

    protected $table = 'logistics_deliveries';

    public const STATUS_PREPARING = 'preparing';

    public const STATUS_IN_TRANSIT = 'in_transit';

    public const STATUS_ARRIVED_AT_SITE = 'arrived_at_site';

    public const STATUS_INSPECTED_AND_RECEIVED = 'inspected_and_received';

    public const STATUS_ISSUE_DAMAGED = 'issue_damaged';

    protected $fillable = [
        'delivery_number',
        'purchase_order_id',
        'project_id',
        'expedition_name',
        'tracking_number',
        'origin',
        'destination',
        'shipment_date',
        'estimated_arrival_date',
        'actual_arrival_date',
        'recipient_name',
        'recipient_phone',
        'status',
        'driver_name',
        'driver_phone',
        'vehicle_plate',
        'bap_attachment_path',
        'notes',
    ];

    protected $casts = [
        'shipment_date' => 'date',
        'estimated_arrival_date' => 'date',
        'actual_arrival_date' => 'date',
    ];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
