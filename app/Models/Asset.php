<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_code',
        'name',
        'category',
        'brand_model',
        'serial_number',
        'year_acquired',
        'purchase_price',
        'condition',
        'status',
        'current_location',
        'assigned_project_id',
        'pic_employee_id',
        'last_maintenance_date',
        'next_maintenance_date',
        'last_calibration_date',
        'next_calibration_date',
        'technical_specifications',
        'notes',
    ];

    protected $casts = [
        'year_acquired' => 'integer',
        'purchase_price' => 'decimal:2',
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'last_calibration_date' => 'date',
        'next_calibration_date' => 'date',
    ];

    public function assignedProject(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'assigned_project_id');
    }

    public function picEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'pic_employee_id');
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(AssetAllocation::class, 'asset_id');
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(AssetMaintenance::class, 'asset_id');
    }
}
