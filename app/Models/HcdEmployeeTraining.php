<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HcdEmployeeTraining extends Model
{
    use HasFactory;

    protected $table = 'hcd_employee_trainings';

    public const STATUS_VALID = 'valid';
    public const STATUS_EXPIRING_SOON = 'expiring_soon';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_IN_TRAINING = 'in_training';

    protected $fillable = [
        'employee_id',
        'training_id',
        'certificate_number',
        'issue_date',
        'expiry_date',
        'status',
        'certificate_file',
        'cost',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function training(): BelongsTo
    {
        return $this->belongsTo(HcdTraining::class, 'training_id');
    }

    public function getComputedStatusAttribute(): string
    {
        if ($this->status === self::STATUS_IN_TRAINING) {
            return self::STATUS_IN_TRAINING;
        }

        if (!$this->expiry_date) {
            return self::STATUS_VALID;
        }

        $today = Carbon::today();
        if ($this->expiry_date->isPast()) {
            return self::STATUS_EXPIRED;
        }

        if ($this->expiry_date->diffInDays($today) <= 30) {
            return self::STATUS_EXPIRING_SOON;
        }

        return self::STATUS_VALID;
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->computed_status) {
            self::STATUS_VALID => 'bg-success-50 text-success-700 border border-success-200',
            self::STATUS_EXPIRING_SOON => 'bg-warning-50 text-warning-main border border-warning-200',
            self::STATUS_EXPIRED => 'bg-danger-50 text-danger-600 border border-danger-200',
            self::STATUS_IN_TRAINING => 'bg-info-50 text-info-main border border-info-200',
            default => 'bg-neutral-100 text-secondary-light',
        };
    }
}
