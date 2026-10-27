<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HsseIncident extends Model
{
    use HasFactory;

    protected $table = 'hsse_incidents';

    public const STATUS_REPORTED = 'reported';
    public const STATUS_INVESTIGATING = 'investigating';
    public const STATUS_ACTION_PENDING = 'action_pending';
    public const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'incident_number',
        'title',
        'incident_type',
        'project_id',
        'incident_date',
        'location',
        'reporter_id',
        'injured_person_id',
        'description',
        'root_cause',
        'immediate_action',
        'capa_action',
        'target_date',
        'status',
        'lost_workdays',
    ];

    protected $casts = [
        'incident_date' => 'datetime',
        'target_date' => 'date',
        'lost_workdays' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'reporter_id');
    }

    public function injuredPerson(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'injured_person_id');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_CLOSED => 'bg-success-50 text-success-700 border border-success-200',
            self::STATUS_INVESTIGATING => 'bg-primary-50 text-primary-600 border border-primary-200',
            self::STATUS_ACTION_PENDING => 'bg-warning-50 text-warning-main border border-warning-200',
            default => 'bg-danger-50 text-danger-600 border border-danger-200',
        };
    }

    public function getTypeBadgeClassAttribute(): string
    {
        return match ($this->incident_type) {
            'Lost Time Injury (LTI)', 'Fatality' => 'bg-danger-main text-white',
            'Medical Treatment Case (MTC)', 'Restricted Work Day (RWC)' => 'bg-danger-50 text-danger-700 border border-danger-200',
            'First Aid Case (FAC)', 'Property Damage', 'Environmental Spill' => 'bg-warning-50 text-warning-700 border border-warning-200',
            default => 'bg-info-50 text-info-700 border border-info-200', // Near Miss
        };
    }
}
