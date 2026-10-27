<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Opportunity;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalesAIOpportunitiesController extends Controller
{
    /**
     * Initial seed of AI Opportunities matched from market intelligence
     */
    protected array $seedOpportunities = [
        [
            'id' => 'AI-2026-001',
            'title' => 'Tank Cleaning & Inspection Campaign 2026',
            'company' => 'PT Kaltim Parna Industri',
            'sector' => 'Oil & Gas',
            'region' => 'Kalimantan Timur',
            'published' => '2026-10-10',
            'closing' => '2026-10-28',
            'source' => 'Portal Pengadaan Publik',
            'type' => 'Tender / RFQ',
            'service' => 'Tank Cleaning · Inspection',
            'score' => 94,
            'estimated_value' => 2400000000,
            'summary' => 'Paket indikatif untuk pembersihan tangki proses, degreasing, dan inspeksi NDT berkala.',
        ],
        [
            'id' => 'AI-2026-002',
            'title' => 'Online Leak Sealing Maintenance Service',
            'company' => 'PT Pupuk Sriwidjaja',
            'sector' => 'Petrochemical',
            'region' => 'Sumatera Selatan',
            'published' => '2026-10-07',
            'closing' => '2026-10-24',
            'source' => 'Website Perusahaan / Tender Board',
            'type' => 'Proposal Request',
            'service' => 'Online Leak Sealing',
            'score' => 89,
            'estimated_value' => 1800000000,
            'summary' => 'Kebutuhan indikatif layanan perbaikan kebocoran steam line & ammonia tanpa penghentian operasi (OLSS).',
        ],
        [
            'id' => 'AI-2026-003',
            'title' => 'Heat Exchanger Cleaning & Condition Assessment',
            'company' => 'PT Vale Indonesia',
            'sector' => 'Mining & Metals',
            'region' => 'Sulawesi Selatan',
            'published' => '2026-10-03',
            'closing' => '2026-10-31',
            'source' => 'Portal Procurement Migas/Mining',
            'type' => 'Bidding',
            'service' => 'Mechanical Cleaning · Inspection',
            'score' => 86,
            'estimated_value' => 1500000000,
            'summary' => 'Peluang indikatif untuk pembersihan berkala dan evaluasi integritas heat exchanger pabrik smelter.',
        ],
        [
            'id' => 'AI-2026-004',
            'title' => 'Flange Management & Hydraulic Bolting Package',
            'company' => 'PT Pertamina Patra Niaga',
            'sector' => 'Oil & Gas',
            'region' => 'DKI Jakarta',
            'published' => '2026-09-28',
            'closing' => '2026-11-05',
            'source' => 'Portal Tender BUMN',
            'type' => 'Tender / RFQ',
            'service' => 'Flange Management',
            'score' => 82,
            'estimated_value' => 950000000,
            'summary' => 'Sinyal peluang jasa flange management, torque tightening, dan hydraulic tensioning pemeliharaan terminal BBM.',
        ],
        [
            'id' => 'AI-2026-005',
            'title' => 'Industrial Gas Filter Package Supply & Replacement',
            'company' => 'PT Samator Indo Gas',
            'sector' => 'Industrial & Manufacturing',
            'region' => 'Jawa Timur',
            'published' => '2026-09-20',
            'closing' => '2026-10-30',
            'source' => 'Procurement Notice',
            'type' => 'Procurement Notice',
            'service' => 'Industrial Products',
            'score' => 77,
            'estimated_value' => 650000000,
            'summary' => 'Paket pengadaan elemen gas filter cartridge dan dukungan teknis instalasi commissioning.',
        ],
        [
            'id' => 'AI-2026-006',
            'title' => 'Turbine Lube Oil Flushing & Filtration',
            'company' => 'PT Pupuk Kalimantan Timur',
            'sector' => 'Petrochemical',
            'region' => 'Kalimantan Timur',
            'published' => '2026-10-12',
            'closing' => '2026-11-15',
            'source' => 'Vendor Management Portal',
            'type' => 'Tender / RFQ',
            'service' => 'Oil Flushing',
            'score' => 91,
            'estimated_value' => 1200000000,
            'summary' => 'Pembersihan dan high velocity hot oil flushing sistem pelumasan turbin kompresor menjelang turnaround.',
        ],
    ];

    /**
     * Display a listing of AI market intelligence opportunities.
     */
    public function index(Request $request): View
    {
        $keyword = trim((string) $request->query('keyword', ''));
        $sector = $request->query('sector');
        $region = $request->query('region');
        $type = $request->query('type');

        $items = collect($this->seedOpportunities)->filter(function ($item) use ($keyword, $sector, $region, $type) {
            if ($keyword) {
                $searchable = strtolower($item['title'].' '.$item['company'].' '.$item['service'].' '.$item['summary']);
                if (! str_contains($searchable, strtolower($keyword))) {
                    return false;
                }
            }
            if ($sector && $item['sector'] !== $sector) {
                return false;
            }
            if ($region && $item['region'] !== $region) {
                return false;
            }
            if ($type && $item['type'] !== $type) {
                return false;
            }

            return true;
        })->values();

        $sectors = collect($this->seedOpportunities)->pluck('sector')->unique()->sort();
        $regions = collect($this->seedOpportunities)->pluck('region')->unique()->sort();
        $types = collect($this->seedOpportunities)->pluck('type')->unique()->sort();

        // Already converted titles in database to mark converted items
        $convertedTitles = Opportunity::pluck('title')->toArray();

        $customers = Customer::orderBy('company_name')->get();
        $users = User::orderBy('name')->get();

        return view('pages.sales.ai_opportunities.index', compact(
            'items',
            'sectors',
            'regions',
            'types',
            'convertedTitles',
            'customers',
            'users',
            'keyword',
            'sector',
            'region',
            'type'
        ));
    }

    /**
     * Convert an AI opportunity to an active pipeline opportunity.
     */
    public function convert(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'estimated_value' => 'required|numeric|min:0',
            'owner_id' => 'nullable|exists:users,id',
            'target_close_date' => 'nullable|date',
            'next_step' => 'nullable|string',
        ]);

        // Find or create customer
        $customer = Customer::firstOrCreate(
            ['company_name' => $validated['company']],
            [
                'pic_name' => 'PIC '.$validated['company'],
                'industry' => $request->input('sector', 'Industrial Services'),
                'location' => $request->input('region', 'Indonesia'),
            ]
        );

        $ownerId = $validated['owner_id'] ?? auth()->id() ?? 1;

        Opportunity::create([
            'customer_id' => $customer->id,
            'owner_id' => $ownerId,
            'title' => $validated['title'],
            'estimated_value' => $validated['estimated_value'],
            'stage' => Opportunity::STAGE_QUALIFICATION,
            'next_step' => $validated['next_step'] ?? 'Klarifikasi spesifikasi & hubungi PIC pengadaan',
            'target_close_date' => $validated['target_close_date'] ?? now()->addMonth()->toDateString(),
        ]);

        return redirect()->route('dashboard-admin.sales.opportunities.index')
            ->with('success', "Peluang '{$validated['title']}' berhasil ditambahkan ke Sales Pipeline!");
    }
}
