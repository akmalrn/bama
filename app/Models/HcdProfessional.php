<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HcdProfessional extends Model
{
    use HasFactory;

    protected $table = 'hcd_professionals';

    public const AVAILABILITY_AVAILABLE = 'available';
    public const AVAILABILITY_MOBILIZED = 'mobilized';
    public const AVAILABILITY_STANDBY = 'standby';
    public const AVAILABILITY_BLACKLISTED = 'blacklisted';

    protected $fillable = [
        'full_name',
        'specialization',
        'category',
        'contact_email',
        'contact_phone',
        'city',
        'experience_years',
        'daily_rate',
        'availability_status',
        'current_project_id',
        'certifications_summary',
        'cv_file',
        'rating',
        'notes',
    ];

    protected $casts = [
        'daily_rate' => 'decimal:2',
        'rating' => 'decimal:2',
        'experience_years' => 'integer',
    ];

    public function currentProject(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'current_project_id');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->availability_status) {
            self::AVAILABILITY_AVAILABLE => 'bg-success-50 text-success-700 border border-success-200',
            self::AVAILABILITY_MOBILIZED => 'bg-primary-50 text-primary-600 border border-primary-200',
            self::AVAILABILITY_STANDBY => 'bg-warning-50 text-warning-main border border-warning-200',
            self::AVAILABILITY_BLACKLISTED => 'bg-danger-50 text-danger-600 border border-danger-200',
            default => 'bg-neutral-100 text-secondary-light',
        };
    }
}
