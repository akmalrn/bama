<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\TaxFiling;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FinanceTaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $now = Carbon::now();
        $year = (int) $now->year;

        $samples = [
            [
                'tax_period_month' => 1,
                'tax_period_year' => $year,
                'tax_type' => TaxFiling::TYPE_PPN,
                'gross_base_amount' => 250000000.00,
                'output_tax' => 27500000.00,
                'input_tax' => 12500000.00,
                'tax_due' => 15000000.00,
                'ntpn' => 'NTPN9812A8721B41',
                'payment_date' => $now->copy()->subMonths(8)->toDateString(),
                'reporting_date' => $now->copy()->subMonths(8)->addDays(5)->toDateString(),
                'status' => TaxFiling::STATUS_REPORTED,
                'bpe_number' => 'BPE-DJP-2026-01-99812',
                'notes' => 'SPT Masa PPN 11% Masa Januari 2026 - Status Selesai Dilaporkan.',
            ],
            [
                'tax_period_month' => 2,
                'tax_period_year' => $year,
                'tax_type' => TaxFiling::TYPE_PPN,
                'gross_base_amount' => 450000000.00,
                'output_tax' => 49500000.00,
                'input_tax' => 24500000.00,
                'tax_due' => 25000000.00,
                'ntpn' => 'NTPN7716C5420K99',
                'payment_date' => $now->copy()->subMonths(7)->toDateString(),
                'reporting_date' => $now->copy()->subMonths(7)->addDays(4)->toDateString(),
                'status' => TaxFiling::STATUS_REPORTED,
                'bpe_number' => 'BPE-DJP-2026-02-11029',
                'notes' => 'SPT Masa PPN Februari 2026.',
            ],
            [
                'tax_period_month' => 3,
                'tax_period_year' => $year,
                'tax_type' => TaxFiling::TYPE_PPH_23,
                'gross_base_amount' => 180000000.00,
                'output_tax' => 0.00,
                'input_tax' => 0.00,
                'tax_due' => 3600000.00,
                'ntpn' => 'NTPN1298X4412M01',
                'payment_date' => $now->copy()->subMonths(6)->toDateString(),
                'reporting_date' => $now->copy()->subMonths(6)->addDays(6)->toDateString(),
                'status' => TaxFiling::STATUS_REPORTED,
                'bpe_number' => 'BPE-EBUPOT-2026-03-8871',
                'notes' => 'e-Bupot Unifikasi PPh 23 Masa Maret.',
            ],
        ];

        foreach ($samples as $s) {
            TaxFiling::updateOrCreate(
                [
                    'tax_period_month' => $s['tax_period_month'],
                    'tax_period_year' => $s['tax_period_year'],
                    'tax_type' => $s['tax_type'],
                ],
                array_merge($s, ['created_by' => $user ? $user->id : null])
            );
        }
    }
}
