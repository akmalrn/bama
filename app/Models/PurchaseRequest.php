<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PurchaseRequest extends Model
{
    use HasFactory;

    protected $table = 'purchase_requests';

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PENDING_APPROVAL = 'pending_approval';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_CONVERTED_TO_PO = 'converted_to_po';

    public const PRIORITIES = [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
        'urgent' => 'Urgent',
    ];

    public const CATEGORIES = [
        'Piping & Valve',
        'Heavy Equipment & Tools',
        'Consumables & Gas',
        'Safety & PPE',
        'Subcon Service',
        'Electrical & Automation',
        'General Supplies',
    ];

    protected $fillable = [
        'pr_number',
        'title',
        'project_id',
        'requested_by',
        'approved_by',
        'request_date',
        'required_date',
        'priority',
        'category',
        'estimated_total',
        'status',
        'justification',
        'approval_notes',
        'created_by',
    ];

    protected $casts = [
        'request_date' => 'date',
        'required_date' => 'date',
        'estimated_total' => 'decimal:2',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'requested_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseRequestItem::class, 'purchase_request_id');
    }

    public function purchaseOrder(): HasOne
    {
        return $this->hasOne(PurchaseOrder::class, 'purchase_request_id');
    }
}
