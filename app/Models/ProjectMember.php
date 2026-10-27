<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectMember extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_RELEASED = 'released';

    protected $table = 'project_members';

    protected $fillable = [
        'project_id',
        'employee_id',
        'role',
        'discipline',
        'assigned_date',
        'rating',
        'performance_notes',
        'status',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'rating' => 'decimal:1',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
