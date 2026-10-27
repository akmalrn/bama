<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    public const STATUS_PLANNING = 'planning';

    public const STATUS_ON_PROGRESS = 'on_progress';

    public const STATUS_ON_HOLD = 'on_hold';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_PLANNING,
        self::STATUS_ON_PROGRESS,
        self::STATUS_ON_HOLD,
        self::STATUS_COMPLETED,
        self::STATUS_CANCELLED,
    ];

    public const RISK_LOW = 'low';

    public const RISK_MEDIUM = 'medium';

    public const RISK_HIGH = 'high';

    public const RISKS = [
        self::RISK_LOW,
        self::RISK_MEDIUM,
        self::RISK_HIGH,
    ];

    protected $table = 'projects';

    protected $fillable = [
        'opportunity_id',
        'customer_id',
        'project_manager_id',
        'project_code',
        'title',
        'contract_value',
        'budget',
        'actual_cost',
        'start_date',
        'end_date',
        'status',
        'progress_percentage',
        'planned_progress_percentage',
        'location',
        'category',
        'sector',
        'procurement_type',
        'risk_level',
        'readiness_percentage',
        'quality_percentage',
        'hsse_percentage',
        'man_hours',
        'invoice_submitted',
        'invoice_paid',
        'notes',
    ];

    protected $casts = [
        'contract_value' => 'decimal:2',
        'budget' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'invoice_submitted' => 'decimal:2',
        'invoice_paid' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'progress_percentage' => 'integer',
        'planned_progress_percentage' => 'integer',
        'readiness_percentage' => 'integer',
        'quality_percentage' => 'integer',
        'hsse_percentage' => 'integer',
        'man_hours' => 'integer',
    ];

    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function projectManager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'project_manager_id');
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(ProjectMilestone::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(ProjectMember::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ProjectDocument::class);
    }

    public function costEntries(): HasMany
    {
        return $this->hasMany(ProjectCostEntry::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function getNameAttribute(): string
    {
        return (string) ($this->title ?? '');
    }

    public function getCommittedCostAttribute(): float
    {
        $entryCommitment = (float) $this->costEntries()->where('type', ProjectCostEntry::TYPE_COMMITMENT)->sum('amount');
        if ($entryCommitment > 0) {
            return $entryCommitment;
        }

        // Default heuristic based on remaining budget for active project
        return (float) round((float) $this->budget * 0.15, 2);
    }

    // --- EVM & Helper Accessors ---

    public function getPlannedValueAttribute(): float
    {
        $budget = (float) $this->budget;
        $planned = (float) $this->planned_progress_percentage;

        return $budget * ($planned / 100);
    }

    public function getEarnedValueAttribute(): float
    {
        $budget = (float) $this->budget;
        $actual = (float) $this->progress_percentage;

        return $budget * ($actual / 100);
    }

    public function getCpiAttribute(): float
    {
        $ac = (float) $this->actual_cost;
        if ($ac <= 0) {
            return 1.00;
        }

        return round($this->earned_value / $ac, 2);
    }

    public function getSpiAttribute(): float
    {
        $pv = $this->planned_value;
        if ($pv <= 0) {
            return 1.00;
        }

        return round($this->earned_value / $pv, 2);
    }

    public function getEacAttribute(): float
    {
        $cpi = $this->cpi;
        $ac = (float) $this->actual_cost;
        $budget = (float) $this->budget;

        if ($cpi <= 0) {
            return $budget;
        }

        return round($ac + (($budget - $this->earned_value) / $cpi), 2);
    }

    public function getVacAttribute(): float
    {
        return round((float) $this->budget - $this->eac, 2);
    }

    public function getCostVarianceAttribute(): float
    {
        return round($this->earned_value - (float) $this->actual_cost, 2);
    }

    public function getScheduleVarianceAttribute(): float
    {
        return round($this->earned_value - $this->planned_value, 2);
    }

    public function getHealthStatusAttribute(): string
    {
        if (in_array($this->status, [self::STATUS_COMPLETED], true)) {
            return 'track';
        }
        if ($this->risk_level === self::RISK_HIGH || $this->cpi < 0.9 || $this->spi < 0.85) {
            return 'risk';
        }
        if ($this->risk_level === self::RISK_MEDIUM || $this->cpi < 1.0 || $this->spi < 1.0) {
            return 'watch';
        }

        return 'track';
    }

    /**
     * Local scope to filter projects based on user access permissions.
     */
    public function scopeAccessibleBy(Builder $query, ?User $user = null): Builder
    {
        $user ??= auth()->user();

        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        // Full access for super-admin, God Admin, sales-director, pmo-manager, or view_all permission
        if (
            $user->hasRole(['super-admin', 'God Admin', 'sales-director', 'pmo-manager']) ||
            $user->can('pmo.project.view_all')
        ) {
            return $query;
        }

        // Scoped access for PM or Opportunity owner
        return $query->where(function (Builder $builder) use ($user) {
            $matched = false;

            if ($user->employee) {
                $builder->where('project_manager_id', $user->employee->id);
                $matched = true;
            }

            if ($matched) {
                $builder->orWhereHas('opportunity', function (Builder $subQuery) use ($user) {
                    $subQuery->where('owner_id', $user->id);
                });
            } else {
                $builder->whereHas('opportunity', function (Builder $subQuery) use ($user) {
                    $subQuery->where('owner_id', $user->id);
                });
            }
        });
    }
}
