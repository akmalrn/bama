<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FinanceInvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();
        $projects = Project::all();
        $user = User::first();

        if ($customers->isEmpty()) {
            return;
        }

        $now = Carbon::now();

        $sampleInvoices = [
            [
                'invoice_number' => 'INV/2026/01/001',
                'customer_index' => 0,
                'project_index' => 0,
                'title' => 'Termin 1 (DP 30%) - Pekerjaan Tank Cleaning & Blasting Refinery Unit',
                'invoice_date' => $now->copy()->subDays(60)->toDateString(),
                'due_date' => $now->copy()->subDays(30)->toDateString(),
                'subtotal' => 250000000.00,
                'tax_rate' => 11.00,
                'tax_amount' => 27500000.00,
                'pph_type' => 'pph_23',
                'pph_rate' => 2.00,
                'pph_amount' => 5000000.00,
                'total_amount' => 277500000.00,
                'paid_amount' => 277500000.00,
                'status' => Invoice::STATUS_PAID,
                'payment_terms' => 'Net 30 Days',
                'notes' => 'Pembayaran lunas via transfer BNI.',
                'payments' => [
                    [
                        'payment_date' => $now->copy()->subDays(35)->toDateString(),
                        'amount' => 277500000.00,
                        'payment_method' => 'Transfer Bank',
                        'reference_no' => 'TRF-BNI-9821731',
                        'notes' => 'Pelunasan invoice termin 1 100%.',
                    ],
                ],
            ],
            [
                'invoice_number' => 'INV/2026/02/002',
                'customer_index' => 1,
                'project_index' => 1,
                'title' => 'Termin 2 (Progress 50%) - Hydrotesting Pipeline Offshore Platform',
                'invoice_date' => $now->copy()->subDays(45)->toDateString(),
                'due_date' => $now->copy()->subDays(15)->toDateString(),
                'subtotal' => 450000000.00,
                'tax_rate' => 11.00,
                'tax_amount' => 49500000.00,
                'pph_type' => 'pph_4_2',
                'pph_rate' => 2.65,
                'pph_amount' => 11925000.00,
                'total_amount' => 499500000.00,
                'paid_amount' => 250000000.00,
                'status' => Invoice::STATUS_PARTIAL,
                'payment_terms' => 'Net 30 Days',
                'notes' => 'Klien membayar 50% terlebih dahulu, sisa menunggu revisi BAST.',
                'payments' => [
                    [
                        'payment_date' => $now->copy()->subDays(20)->toDateString(),
                        'amount' => 250000000.00,
                        'payment_method' => 'Transfer Bank',
                        'reference_no' => 'TRF-MDR-8829101',
                        'notes' => 'Pembayaran parsial 1.',
                    ],
                ],
            ],
            [
                'invoice_number' => 'INV/2026/03/003',
                'customer_index' => 2,
                'project_index' => 2,
                'title' => 'Termin 1 (DP 20%) - Pengadaan Sparepart & Fabrikasi Spool Piping',
                'invoice_date' => $now->copy()->subDays(40)->toDateString(),
                'due_date' => $now->copy()->subDays(10)->toDateString(),
                'subtotal' => 180000000.00,
                'tax_rate' => 11.00,
                'tax_amount' => 19800000.00,
                'pph_type' => 'pph_23',
                'pph_rate' => 2.00,
                'pph_amount' => 3600000.00,
                'total_amount' => 199800000.00,
                'paid_amount' => 0.00,
                'status' => Invoice::STATUS_OVERDUE,
                'payment_terms' => 'Net 30 Days',
                'notes' => 'Faktur fisik sudah diserahkan ke Finance Klien, sedang dalam proses batch approval.',
                'payments' => [],
            ],
            [
                'invoice_number' => 'INV/2026/03/004',
                'customer_index' => 3,
                'project_index' => 3,
                'title' => 'Termin Akhir (BAST 100%) - Overhaul Water Injection Pump & Alignment',
                'invoice_date' => $now->copy()->subDays(10)->toDateString(),
                'due_date' => $now->copy()->addDays(20)->toDateString(),
                'subtotal' => 320000000.00,
                'tax_rate' => 11.00,
                'tax_amount' => 35200000.00,
                'pph_type' => 'pph_23',
                'pph_rate' => 2.00,
                'pph_amount' => 6400000.00,
                'total_amount' => 355200000.00,
                'paid_amount' => 0.00,
                'status' => Invoice::STATUS_SENT,
                'payment_terms' => 'Net 30 Days',
                'notes' => 'Dokumen penagihan lengkap dengan lampiran BAST dan time sheet tim teknisi.',
                'payments' => [],
            ],
            [
                'invoice_number' => 'INV/2026/04/005',
                'customer_index' => 0,
                'project_index' => 0,
                'title' => 'Draft Faktur - Additional Works Repair Flange & NDT Ultrasonic',
                'invoice_date' => $now->copy()->toDateString(),
                'due_date' => $now->copy()->addDays(30)->toDateString(),
                'subtotal' => 85000000.00,
                'tax_rate' => 11.00,
                'tax_amount' => 9350000.00,
                'pph_type' => 'pph_23',
                'pph_rate' => 2.00,
                'pph_amount' => 1700000.00,
                'total_amount' => 94350000.00,
                'paid_amount' => 0.00,
                'status' => Invoice::STATUS_DRAFT,
                'payment_terms' => 'Net 30 Days',
                'notes' => 'Draft untuk review PM sebelum diterbitkan.',
                'payments' => [],
            ],
        ];

        foreach ($sampleInvoices as $item) {
            $customer = $customers->get($item['customer_index']) ?? $customers->first();
            $project = $projects->get($item['project_index']) ?? $projects->first();

            $invoice = Invoice::updateOrCreate(
                ['invoice_number' => $item['invoice_number']],
                [
                    'customer_id' => $customer->id,
                    'project_id' => $project ? $project->id : null,
                    'title' => $item['title'],
                    'invoice_date' => $item['invoice_date'],
                    'due_date' => $item['due_date'],
                    'subtotal' => $item['subtotal'],
                    'tax_rate' => $item['tax_rate'],
                    'tax_amount' => $item['tax_amount'],
                    'pph_type' => $item['pph_type'],
                    'pph_rate' => $item['pph_rate'],
                    'pph_amount' => $item['pph_amount'],
                    'total_amount' => $item['total_amount'],
                    'paid_amount' => $item['paid_amount'],
                    'status' => $item['status'],
                    'payment_terms' => $item['payment_terms'],
                    'notes' => $item['notes'],
                    'created_by' => $user ? $user->id : null,
                ]
            );

            foreach ($item['payments'] as $payment) {
                InvoicePayment::firstOrCreate(
                    [
                        'invoice_id' => $invoice->id,
                        'reference_no' => $payment['reference_no'],
                    ],
                    [
                        'payment_date' => $payment['payment_date'],
                        'amount' => $payment['amount'],
                        'payment_method' => $payment['payment_method'],
                        'notes' => $payment['notes'],
                        'created_by' => $user ? $user->id : null,
                    ]
                );
            }
        }
    }
}
