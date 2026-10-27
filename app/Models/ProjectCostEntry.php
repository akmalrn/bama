<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectCostEntry extends Model
{
    use HasFactory;

    public const TYPE_ACTUAL = 'actual';
    public const TYPE_COMMITMENT = 'commitment';

    public const CATEGORIES = [
        'Personnel / Manpower',
        'Equipment & Tools',
        'Material & Consumables',
        'Subcontractor / Services',
        'Mobilization & Transport',
        'Accommodation & Meals',
        'HSSE & Safety',
        'Testing, QA/QC & Certification',
        'Freight & Logistics',
        'Other Direct Costs',
    ];

    protected $table = 'project_cost_entries';

    protected $fillable = [
        'project_id',
        'entry_date',
        'cost_category',
        'cost_subcategory',
        'type',
        'amount',
        'payment_status',
        'tax_amount',
        'pph_amount',
        'vendor',
        'reference_no',
        'notes',
        'attachment_path',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'pph_amount' => 'decimal:2',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
