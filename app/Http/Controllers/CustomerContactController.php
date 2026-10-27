<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\SalesActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerContactController extends Controller
{
    /**
     * Display a listing of customer contacts.
     */
    public function index(Request $request): View
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('pic_name', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('industry', 'like', "%{$search}%")
                    ->orWhere('pic_email', 'like', "%{$search}%")
                    ->orWhere('pic_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('industry')) {
            $query->where('industry', $request->query('industry'));
        }

        $customers = $query->orderBy('company_name')->paginate(15)->withQueryString();

        // Metrics breakdown
        $allCustomers = Customer::all();
        $totalContacts = $allCustomers->count();

        // Grouping by industry/sector
        $sectors = $allCustomers->groupBy('industry')->map->count()->sortDesc();

        // Count of activities by customer
        $recentActivities = SalesActivity::with('opportunity.customer')
            ->latest('activity_at')
            ->take(5)
            ->get();

        $industries = Customer::whereNotNull('industry')->pluck('industry')->unique()->sort();

        // All registered companies for dropdown selection
        $registeredCompanies = Customer::orderBy('company_name')->get(['id', 'company_name', 'industry', 'location']);

        return view('pages.sales.contacts.index', compact(
            'customers',
            'totalContacts',
            'sectors',
            'recentActivities',
            'industries',
            'registeredCompanies'
        ));
    }

    /**
     * Store a newly created contact / customer PIC.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'pic_name' => 'required|string|max:255',
            'pic_phone' => 'nullable|string|max:50',
            'pic_email' => 'nullable|email|max:255',
        ]);

        Customer::create($validated);

        return redirect()->route('dashboard-admin.sales.contacts.index')
            ->with('success', 'Customer Contact baru berhasil ditambahkan.');
    }

    /**
     * Update the specified customer contact.
     */
    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'pic_name' => 'required|string|max:255',
            'pic_phone' => 'nullable|string|max:50',
            'pic_email' => 'nullable|email|max:255',
        ]);

        $customer->update($validated);

        return redirect()->route('dashboard-admin.sales.contacts.index')
            ->with('success', 'Customer Contact berhasil diperbarui.');
    }

    /**
     * Remove the specified contact (customer).
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('dashboard-admin.sales.contacts.index')
            ->with('success', 'Customer Contact berhasil dihapus.');
    }
}
