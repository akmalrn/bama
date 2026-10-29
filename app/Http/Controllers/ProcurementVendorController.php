<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ProcurementVendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProcurementVendorController extends Controller
{
    /**
     * Display vendor management index.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');
        $categoryFilter = $request->query('category', 'all');
        $search = $request->query('search');

        $query = ProcurementVendor::withCount('purchaseOrders');

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($categoryFilter !== 'all') {
            $query->where('category', $categoryFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('vendor_code', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $allVendors = ProcurementVendor::all();
        $counts = [
            'all' => $allVendors->count(),
            'active' => $allVendors->where('status', ProcurementVendor::STATUS_ACTIVE)->count(),
            'under_review' => $allVendors->where('status', ProcurementVendor::STATUS_UNDER_REVIEW)->count(),
            'blacklisted' => $allVendors->where('status', ProcurementVendor::STATUS_BLACKLISTED)->count(),
        ];

        $vendors = $query->orderBy('name')->paginate(15)->withQueryString();
        $categories = ProcurementVendor::CATEGORIES;

        $nextCode = 'VND-'.str_pad((string) (ProcurementVendor::count() + 1), 3, '0', STR_PAD_LEFT);

        return view('pages.procurement.vendors.index', compact(
            'vendors',
            'counts',
            'statusFilter',
            'categoryFilter',
            'search',
            'categories',
            'nextCode'
        ));
    }

    /**
     * Store a newly created vendor.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vendor_code' => ['required', 'string', 'max:50', 'unique:procurement_vendors,vendor_code'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:80'],
            'contact_person' => ['nullable', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:100'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'npwp' => ['nullable', 'string', 'max:50'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'bank_account_number' => ['nullable', 'string', 'max:60'],
            'bank_account_holder' => ['nullable', 'string', 'max:150'],
            'payment_terms' => ['required', 'string', 'max:60'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'status' => ['required', 'in:active,under_review,blacklisted'],
            'notes' => ['nullable', 'string'],
        ]);

        ProcurementVendor::create($validated);

        return redirect()->route('dashboard-admin.procurement.vendors.index')
            ->with('success', "Vendor {$validated['name']} ({$validated['vendor_code']}) berhasil didaftarkan.");
    }

    /**
     * Update the specified vendor.
     */
    public function update(Request $request, ProcurementVendor $vendor): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:80'],
            'contact_person' => ['nullable', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:100'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'npwp' => ['nullable', 'string', 'max:50'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'bank_account_number' => ['nullable', 'string', 'max:60'],
            'bank_account_holder' => ['nullable', 'string', 'max:150'],
            'payment_terms' => ['required', 'string', 'max:60'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'status' => ['required', 'in:active,under_review,blacklisted'],
            'notes' => ['nullable', 'string'],
        ]);

        $vendor->update($validated);

        return redirect()->route('dashboard-admin.procurement.vendors.index')
            ->with('success', "Data vendor {$vendor->name} berhasil diperbarui.");
    }

    /**
     * Remove the specified vendor.
     */
    public function destroy(ProcurementVendor $vendor): RedirectResponse
    {
        if ($vendor->purchaseOrders()->count() > 0) {
            return redirect()->route('dashboard-admin.procurement.vendors.index')
                ->with('error', "Tidak dapat menghapus vendor {$vendor->name} karena masih memiliki riwayat Purchase Order.");
        }

        $name = $vendor->name;
        $vendor->delete();

        return redirect()->route('dashboard-admin.procurement.vendors.index')
            ->with('success', "Vendor {$name} berhasil dihapus.");
    }
}
