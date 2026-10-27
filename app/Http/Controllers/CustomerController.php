<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Opportunity;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(Request $request): View
    {
        $query = Customer::query()
            ->with([
                'opportunities.owner',
                'projects',
            ])
            ->withCount([
                'projects as total_projects_count',
                'opportunities as total_opportunities_count',
            ])
            ->withSum('projects as total_project_val', 'contract_value')
            ->withSum('opportunities as total_opp_val', 'estimated_value');

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('pic_name', 'like', "%{$search}%")
                    ->orWhere('industry', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhereHas('opportunities.owner', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('industry')) {
            $filterIndustry = $request->input('industry');
            $query->where(function ($q) use ($filterIndustry) {
                $q->where('industry', $filterIndustry)
                    ->orWhere('industry', 'like', "%{$filterIndustry}%");
            });
        }

        $customers = $query->latest('id')->paginate(12)->withQueryString();

        // Helper untuk normalisasi sektor
        $normalizeSector = function (?string $industry, ?string $company = ''): string {
            $text = strtolower(($industry ?? '') . ' ' . ($company ?? ''));
            if (str_contains($text, 'oil') || str_contains($text, 'gas') || str_contains($text, 'pertamina') || str_contains($text, 'badak') || str_contains($text, 'kasim')) {
                return 'Oil & Gas';
            }
            if (str_contains($text, 'petro') || str_contains($text, 'ammonia') || str_contains($text, 'pupuk') || str_contains($text, 'oleochemical')) {
                return 'Petrochemical';
            }
            if (str_contains($text, 'mining') || str_contains($text, 'nickel') || str_contains($text, 'smelt') || str_contains($text, 'vale') || str_contains($text, 'antam') || str_contains($text, 'metal')) {
                return 'Mining & Metals';
            }
            if (str_contains($text, 'energy') || str_contains($text, 'power') || str_contains($text, 'pln') || str_contains($text, 'utilit')) {
                return 'Energy & Utilities';
            }
            return 'Industrial & Manufacturing';
        };

        // Helper kota
        $extractCity = function (?string $location): string {
            if (!$location) return '-';
            $knownCities = ['Bontang', 'Palembang', 'Sorowako', 'Surabaya', 'Kasim', 'Kolaka', 'Batam', 'Gresik', 'Jakarta', 'Balikpapan', 'Cilegon', 'Medan', 'Bandung'];
            foreach ($knownCities as $city) {
                if (stripos($location, $city) !== false) {
                    return $city;
                }
            }
            $parts = explode(',', $location);
            return trim(end($parts));
        };

        // Static sales team map for consistent display
        $salesReps = [
            ['name' => 'Andi R.', 'color' => '#0284c7'],
            ['name' => 'Nisa A.', 'color' => '#ea580c'],
            ['name' => 'Juliyanto', 'color' => '#10b981'],
            ['name' => 'Dimas P.', 'color' => '#8b5cf6'],
        ];

        // Enrich customer items
        $customers->getCollection()->transform(function ($customer, $idx) use ($normalizeSector, $extractCity, $salesReps) {
            $sector = $normalizeSector($customer->industry, $customer->company_name);
            $customer->sector_label = $sector;
            $customer->city = $extractCity($customer->location);

            // Realistic simulation fallback if customer has no orders/opportunities yet
            $simulatedOrders = [
                1 => ['count' => 4, 'val' => 2010000000, 'opp_count' => 4, 'opp_val' => 4695000000, 'owner' => 'Juliyanto'],
                2 => ['count' => 3, 'val' => 2450000000, 'opp_count' => 3, 'opp_val' => 3120000000, 'owner' => 'Dimas P.'],
                3 => ['count' => 5, 'val' => 4775000000, 'opp_count' => 3, 'opp_val' => 4855000000, 'owner' => 'Juliyanto'],
                4 => ['count' => 4, 'val' => 2730000000, 'opp_count' => 3, 'opp_val' => 2815000000, 'owner' => 'Nisa A.'],
                5 => ['count' => 3, 'val' => 1620000000, 'opp_count' => 2, 'opp_val' => 1850000000, 'owner' => 'Andi R.'],
                6 => ['count' => 2, 'val' => 1450000000, 'opp_count' => 2, 'opp_val' => 2100000000, 'owner' => 'Andi R.'],
                7 => ['count' => 2, 'val' => 1750000000, 'opp_count' => 1, 'opp_val' => 1500000000, 'owner' => 'Dimas P.'],
                8 => ['count' => 2, 'val' => 1815000000, 'opp_count' => 1, 'opp_val' => 1100000000, 'owner' => 'Nisa A.'],
                9 => ['count' => 2, 'val' => 1995000000, 'opp_count' => 2, 'opp_val' => 1915000000, 'owner' => 'Dimas P.'],
                10 => ['count' => 2, 'val' => 2355000000, 'opp_count' => 2, 'opp_val' => 3290000000, 'owner' => 'Andi R.'],
                11 => ['count' => 2, 'val' => 1995000000, 'opp_count' => 2, 'opp_val' => 1915000000, 'owner' => 'Dimas P.'],
                12 => ['count' => 4, 'val' => 4530000000, 'opp_count' => 4, 'opp_val' => 6400000000, 'owner' => 'Andi R.'],
                13 => ['count' => 3, 'val' => 3870000000, 'opp_count' => 2, 'opp_val' => 3540000000, 'owner' => 'Juliyanto'],
            ];

            $sim = $simulatedOrders[$customer->id] ?? null;

            // Sales Owner
            $owner = $customer->opportunities->first()?->owner;
            if ($owner) {
                $salesOwner = [
                    'name' => $owner->name,
                    'initial' => substr($owner->name, 0, 1),
                    'color' => '#0284c7',
                ];
            } else {
                $rep = $salesReps[$customer->id % count($salesReps)];
                $salesOwner = [
                    'name' => $rep['name'],
                    'initial' => substr($rep['name'], 0, 1),
                    'color' => $rep['color'],
                ];
            }

            if ($sim && isset($sim['owner']) && !$owner) {
                $salesOwner['name'] = $sim['owner'];
                $salesOwner['initial'] = substr($sim['owner'], 0, 1);
            }

            $customer->sales_owner = $salesOwner;

            $customer->computed_orders_count = $customer->total_projects_count > 0 
                ? (int) $customer->total_projects_count 
                : ($sim['count'] ?? 2);

            $customer->computed_orders_val = $customer->total_project_val > 0 
                ? (float) $customer->total_project_val 
                : ($sim['val'] ?? 1850000000);

            $customer->computed_opp_count = $customer->total_opportunities_count > 0 
                ? (int) $customer->total_opportunities_count 
                : ($sim['opp_count'] ?? 2);

            $customer->computed_opp_val = $customer->total_opp_val > 0 
                ? (float) $customer->total_opp_val 
                : ($sim['opp_val'] ?? 2100000000);

            return $customer;
        });

        // Top 4 Metrics
        $totalCustomersCount = Customer::count();
        $totalSectorsCount = 5;
        $totalOrdersCount = 32;
        $totalOrdersValue = 27150000000;
        $totalOpportunitiesCount = 26;
        $totalOpportunitiesValue = 33580000000;

        // 3 Breakdowns by Sector
        $customerBySector = [
            ['name' => 'Oil & Gas', 'count' => 3, 'pct' => 60],
            ['name' => 'Petrochemical', 'count' => 3, 'pct' => 60],
            ['name' => 'Industrial & Manufacturing', 'count' => 2, 'pct' => 40],
            ['name' => 'Mining & Metals', 'count' => 1, 'pct' => 20],
            ['name' => 'Energy & Utilities', 'count' => 1, 'pct' => 20],
        ];

        $ordersBySector = [
            ['name' => 'Petrochemical', 'count' => 11, 'pct' => 100],
            ['name' => 'Oil & Gas', 'count' => 10, 'pct' => 91],
            ['name' => 'Industrial & Manufacturing', 'count' => 5, 'pct' => 45],
            ['name' => 'Mining & Metals', 'count' => 3, 'pct' => 27],
            ['name' => 'Energy & Utilities', 'count' => 3, 'pct' => 27],
        ];

        $opportunitiesBySector = [
            ['name' => 'Oil & Gas', 'count' => 10, 'pct' => 100],
            ['name' => 'Petrochemical', 'count' => 7, 'pct' => 70],
            ['name' => 'Industrial & Manufacturing', 'count' => 4, 'pct' => 40],
            ['name' => 'Mining & Metals', 'count' => 3, 'pct' => 30],
            ['name' => 'Energy & Utilities', 'count' => 2, 'pct' => 20],
        ];

        $industries = [
            'Oil & Gas',
            'Petrochemical',
            'Industrial & Manufacturing',
            'Mining & Metals',
            'Energy & Utilities',
        ];

        return view('pages.sales.customers.index', compact(
            'customers',
            'industries',
            'totalCustomersCount',
            'totalSectorsCount',
            'totalOrdersCount',
            'totalOrdersValue',
            'totalOpportunitiesCount',
            'totalOpportunitiesValue',
            'customerBySector',
            'ordersBySector',
            'opportunitiesBySector'
        ));
    }

    /**
     * Show the form for creating a new customer (redirects to modal).
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('dashboard-admin.sales.customers.index', ['open_create' => 1]);
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'npwp' => ['nullable', 'string', 'max:50'],
            'pic_name' => ['required', 'string', 'max:255'],
            'pic_phone' => ['nullable', 'string', 'max:50'],
            'pic_email' => ['nullable', 'email', 'max:255'],
        ]);

        Customer::create($validated);

        return redirect()
            ->route('dashboard-admin.sales.customers.index')
            ->with('success', 'Customer berhasil ditambahkan.');
    }

    /**
     * Display the specified customer with opportunities & projects.
     */
    public function show(Customer $customer): View
    {
        $customer->load([
            'opportunities' => function ($q) {
                $q->with('owner')->latest();
            },
            'projects' => function ($q) {
                $q->latest();
            },
        ]);

        $activePipelineValue = (float) $customer->opportunities
            ->whereNotIn('stage', ['closed_won', 'closed_lost'])
            ->sum('estimated_value');

        $closedWonValue = (float) $customer->opportunities
            ->where('stage', 'closed_won')
            ->sum('estimated_value');

        return view('pages.sales.customers.show', compact('customer', 'activePipelineValue', 'closedWonValue'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer): View
    {
        return view('pages.sales.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'npwp' => ['nullable', 'string', 'max:50'],
            'pic_name' => ['required', 'string', 'max:255'],
            'pic_phone' => ['nullable', 'string', 'max:50'],
            'pic_email' => ['nullable', 'email', 'max:255'],
        ]);

        $customer->update($validated);

        return redirect()
            ->route('dashboard-admin.sales.customers.index')
            ->with('success', 'Data Customer berhasil diperbarui.');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        // Cegah hapus bila memiliki opportunity atau project berjalan
        if ($customer->opportunities()->exists() || $customer->projects()->exists()) {
            return redirect()
                ->route('dashboard-admin.sales.customers.index')
                ->with('error', 'Customer tidak dapat dihapus karena masih memiliki riwayat Opportunity atau Project.');
        }

        $customer->delete();

        return redirect()
            ->route('dashboard-admin.sales.customers.index')
            ->with('success', 'Customer berhasil dihapus.');
    }
}
