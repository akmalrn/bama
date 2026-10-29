<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'allocation_number',
        'asset_id',
        'project_id',
        'requested_by',
        'approved_by',
        'start_date',
        'expected_return_date',
        'actual_return_date',
        'status',
        'destination_site',
        'courier_vehicle',
        'letter_of_handover_number',
        'condition_out',
        'condition_in',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'expected_return_date' => 'date',
        'actual_return_date' => 'date',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'requested_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }
}
