<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcurementVendor extends Model
{
    use HasFactory;

    protected $table = 'procurement_vendors';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_UNDER_REVIEW = 'under_review';

    public const STATUS_BLACKLISTED = 'blacklisted';

    public const CATEGORIES = [
        'Piping, Flange & Fittings',
        'Mechanical & Heavy Equipment',
        'Consumables, Welding & Chemicals',
        'Electrical, Instrument & Automation',
        'Safety, PPE & Environmental',
        'Subcontractor & Field Services',
        'General Hardware & Logistics',
    ];

    protected $fillable = [
        'vendor_code',
        'name',
        'category',
        'contact_person',
        'phone',
        'email',
        'address',
        'city',
        'npwp',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'payment_terms',
        'rating',
        'status',
        'notes',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, 'vendor_id');
    }
}
