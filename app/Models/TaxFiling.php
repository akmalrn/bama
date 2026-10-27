<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxFiling extends Model
{
    use HasFactory;

    public const TYPE_PPN = 'ppn';
    public const TYPE_PPH_21 = 'pph_21';
    public const TYPE_PPH_23 = 'pph_23';
    public const TYPE_PPH_4_2 = 'pph_4_2';
    public const TYPE_PPH_BADAN = 'pph_badan';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PAID = 'paid';
    public const STATUS_REPORTED = 'reported';

    protected $table = 'tax_filings';

    protected $fillable = [
        'tax_period_month',
        'tax_period_year',
        'tax_type',
        'gross_base_amount',
        'output_tax',
        'input_tax',
        'tax_due',
        'ntpn',
        'payment_date',
        'reporting_date',
        'status',
        'bpe_number',
        'bpe_file',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'tax_period_month' => 'integer',
        'tax_period_year' => 'integer',
        'gross_base_amount' => 'decimal:2',
        'output_tax' => 'decimal:2',
        'input_tax' => 'decimal:2',
        'tax_due' => 'decimal:2',
        'payment_date' => 'date',
        'reporting_date' => 'date',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getPeriodLabelAttribute(): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return ($months[$this->tax_period_month] ?? "Bulan {$this->tax_period_month}") . " {$this->tax_period_year}";
    }
}
