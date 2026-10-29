<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Opportunity extends Model
{
    use HasFactory;

    public const STAGE_QUALIFICATION = 'qualification';

    public const STAGE_NEED_ANALYSIS = 'need_analysis';

    public const STAGE_TENDER_RFQ = 'tender_rfq';

    public const STAGE_PROPOSAL_SUBMITTED = 'proposal_submitted';

    public const STAGE_NEGOTIATION = 'negotiation';

    public const STAGE_CLOSED_WON = 'closed_won';

    public const STAGE_CLOSED_LOST = 'closed_lost';

    public const STAGES = [
        self::STAGE_QUALIFICATION,
        self::STAGE_NEED_ANALYSIS,
        self::STAGE_TENDER_RFQ,
        self::STAGE_PROPOSAL_SUBMITTED,
        self::STAGE_NEGOTIATION,
        self::STAGE_CLOSED_WON,
        self::STAGE_CLOSED_LOST,
    ];

    protected $fillable = [
        'customer_id',
        'owner_id',
        'title',
        'service_line',
        'project_location',
        'lead_source',
        'estimated_value',
        'stage',
        'next_step',
        'target_close_date',
        'next_follow_up_date',
        'notes',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
        'target_close_date' => 'date',
        'next_follow_up_date' => 'date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }

    public function salesActivities(): HasMany
    {
        return $this->hasMany(SalesActivity::class);
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Local scope to filter opportunities based on user access permissions.
     */
    public function scopeAccessibleBy(Builder $query, ?User $user = null): Builder
    {
        $user ??= auth()->user();

        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        // Full access for super-admin, God Admin, sales-director, or anyone with view_all permission
        if (
            $user->hasRole(['super-admin', 'God Admin', 'sales-director']) ||
            $user->can('sales.opportunity.view_all')
        ) {
            return $query;
        }

        // Scoped access for view_own (e.g. sales-executive) or fallback by ownership
        return $query->where('owner_id', $user->id);
    }
}
