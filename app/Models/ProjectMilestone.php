<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectMilestone extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_OVERDUE = 'overdue';

    protected $table = 'project_milestones';

    protected $fillable = [
        'project_id',
        'milestone_code',
        'title',
        'planned_start_date',
        'target_date',
        'actual_date',
        'weight_percentage',
        'progress_percentage',
        'status',
        'deliverable',
        'notes',
    ];

    protected $casts = [
        'planned_start_date' => 'date',
        'target_date' => 'date',
        'actual_date' => 'date',
        'weight_percentage' => 'decimal:2',
        'progress_percentage' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
