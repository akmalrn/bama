<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HssePermit extends Model
{
    use HasFactory;

    protected $table = 'hsse_permits';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_UNDER_REVIEW = 'under_review';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_CLOSED = 'closed';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'permit_number',
        'title',
        'permit_type',
        'project_id',
        'location',
        'applicant_id',
        'safety_officer_id',
        'start_time',
        'end_time',
        'risk_level',
        'jsa_content',
        'gas_test_required',
        'gas_test_o2',
        'gas_test_lel',
        'gas_test_co',
        'gas_test_h2s',
        'ppe_requirements',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'gas_test_required' => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'applicant_id');
    }

    public function safetyOfficer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'safety_officer_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'bg-success-50 text-success-700 border border-success-200',
            self::STATUS_APPROVED => 'bg-primary-50 text-primary-600 border border-primary-200',
            self::STATUS_UNDER_REVIEW => 'bg-warning-50 text-warning-main border border-warning-200',
            self::STATUS_REJECTED => 'bg-danger-50 text-danger-600 border border-danger-200',
            self::STATUS_CLOSED => 'bg-neutral-100 text-secondary-light',
            default => 'bg-neutral-100 text-secondary-light border',
        };
    }

    public function getRiskBadgeClassAttribute(): string
    {
        return match (strtolower($this->risk_level)) {
            'critical' => 'bg-danger-main text-white',
            'high' => 'bg-danger-50 text-danger-600 border border-danger-200',
            'medium' => 'bg-warning-50 text-warning-main border border-warning-200',
            default => 'bg-success-50 text-success-700 border border-success-200',
        };
    }
}
