<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesActivity extends Model
{
    use HasFactory;

    public const TYPE_OUTSIDE_MEETING = 'outside_meeting';

    public const TYPE_PROPOSAL_SUBMITTED = 'proposal_submitted';

    public const TYPE_ONLINE_PRESENTATION = 'online_presentation';

    public const TYPE_CUSTOMER_VISIT = 'customer_visit';

    public const TYPES = [
        self::TYPE_OUTSIDE_MEETING,
        self::TYPE_PROPOSAL_SUBMITTED,
        self::TYPE_ONLINE_PRESENTATION,
        self::TYPE_CUSTOMER_VISIT,
    ];

    protected $fillable = [
        'opportunity_id',
        'user_id',
        'type',
        'title',
        'notes',
        'activity_at',
    ];

    protected $casts = [
        'activity_at' => 'datetime',
    ];

    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
