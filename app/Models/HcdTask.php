<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HcdTask extends Model
{
    use HasFactory;

    protected $table = 'hcd_tasks';

    public const PRIORITY_LOW = 'low';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_HIGH = 'high';
    public const PRIORITY_URGENT = 'urgent';

    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_REVIEW = 'review';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_BLOCKED = 'blocked';

    protected $fillable = [
        'task_code',
        'title',
        'description',
        'assigned_to',
        'project_id',
        'department_id',
        'priority',
        'status',
        'due_date',
        'completed_at',
        'estimated_hours',
        'actual_hours',
        'created_by',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
        'estimated_hours' => 'decimal:2',
        'actual_hours' => 'decimal:2',
    ];

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getIsOverdueAttribute(): bool
    {
        if ($this->status === self::STATUS_COMPLETED || !$this->due_date) {
            return false;
        }

        return $this->due_date->isPast();
    }

    public function getPriorityBadgeClassAttribute(): string
    {
        return match ($this->priority) {
            self::PRIORITY_URGENT => 'bg-danger-50 text-danger-600 border border-danger-200',
            self::PRIORITY_HIGH => 'bg-warning-50 text-warning-main border border-warning-200',
            self::PRIORITY_MEDIUM => 'bg-info-50 text-info-main border border-info-200',
            default => 'bg-neutral-100 text-secondary-light',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_COMPLETED => 'bg-success-50 text-success-700 border border-success-200',
            self::STATUS_IN_PROGRESS => 'bg-primary-50 text-primary-600 border border-primary-200',
            self::STATUS_REVIEW => 'bg-purple-50 text-purple-main border border-purple-200',
            self::STATUS_BLOCKED => 'bg-danger-50 text-danger-600 border border-danger-200',
            default => 'bg-neutral-100 text-secondary-light',
        };
    }
}
