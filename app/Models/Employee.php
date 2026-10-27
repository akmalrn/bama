<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'position_id',
        'employee_number',
        'nik',
        'employment_status',
        'work_location',
        'status',
        'daily_rate',
        'skills',
        'education',
        'join_date',
        'phone',
        'emergency_contact_name',
        'emergency_contact_phone',
    ];

    protected $casts = [
        'join_date' => 'date',
        'daily_rate' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function managedProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'project_manager_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(HcdTask::class, 'assigned_to');
    }

    public function employeeTrainings(): HasMany
    {
        return $this->hasMany(HcdEmployeeTraining::class, 'employee_id');
    }

    public function getNameAttribute(): string
    {
        return (string) ($this->user?->name ?? 'Karyawan #' . $this->id);
    }

    public function getEmailAttribute(): string
    {
        return (string) ($this->user?->email ?? '-');
    }

    public function getActiveCertificationsCountAttribute(): int
    {
        return $this->employeeTrainings()->where('status', 'valid')->count();
    }
}
