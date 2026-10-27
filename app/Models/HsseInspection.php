<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HsseInspection extends Model
{
    use HasFactory;

    protected $table = 'hsse_inspections';

    public const RESULT_PASSED = 'passed';
    public const RESULT_PASSED_WITH_NOTES = 'passed_with_notes';
    public const RESULT_FAILED_QUARANTINED = 'failed_quarantined';

    protected $fillable = [
        'inspection_number',
        'item_name',
        'item_category',
        'serial_number',
        'project_id',
        'location',
        'inspector_id',
        'inspection_date',
        'next_inspection_date',
        'result',
        'checklist_findings',
        'corrective_action',
        'tag_color',
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'next_inspection_date' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'inspector_id');
    }

    public function getResultBadgeClassAttribute(): string
    {
        return match ($this->result) {
            self::RESULT_PASSED => 'bg-success-50 text-success-700 border border-success-200',
            self::RESULT_PASSED_WITH_NOTES => 'bg-warning-50 text-warning-main border border-warning-200',
            self::RESULT_FAILED_QUARANTINED => 'bg-danger-50 text-danger-600 border border-danger-200',
            default => 'bg-neutral-100 text-secondary-light',
        };
    }
}
