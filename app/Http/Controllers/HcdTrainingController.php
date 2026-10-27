<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\HcdEmployeeTraining;
use App\Models\HcdTraining;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HcdTrainingController extends Controller
{
    /**
     * Display Training & Development index.
     */
    public function index(Request $request): View
    {
        $activeTab = $request->query('tab', 'certificates'); // certificates, catalog
        $statusFilter = $request->query('status', 'all');
        $categoryFilter = $request->query('category', 'all');
        $employeeFilter = $request->query('employee_id', 'all');
        $search = $request->query('search');

        // All certificates query
        $certQuery = HcdEmployeeTraining::with(['employee.user', 'employee.department', 'training']);

        if ($employeeFilter !== 'all') {
            $certQuery->where('employee_id', $employeeFilter);
        }

        if ($search) {
            $certQuery->where(function ($q) use ($search) {
                $q->where('certificate_number', 'like', "%{$search}%")
                    ->orWhereHas('training', function ($tq) use ($search) {
                        $tq->where('title', 'like', "%{$search}%")
                            ->orWhere('institution', 'like', "%{$search}%");
                    })
                    ->orWhereHas('employee.user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $allCerts = HcdEmployeeTraining::with(['employee.user', 'training'])->get();

        // Calculate statuses
        $counts = [
            'all' => $allCerts->count(),
            'valid' => $allCerts->filter(fn ($c) => $c->computed_status === HcdEmployeeTraining::STATUS_VALID)->count(),
            'expiring_soon' => $allCerts->filter(fn ($c) => $c->computed_status === HcdEmployeeTraining::STATUS_EXPIRING_SOON)->count(),
            'expired' => $allCerts->filter(fn ($c) => $c->computed_status === HcdEmployeeTraining::STATUS_EXPIRED)->count(),
            'in_training' => $allCerts->filter(fn ($c) => $c->computed_status === HcdEmployeeTraining::STATUS_IN_TRAINING)->count(),
        ];

        if ($statusFilter !== 'all') {
            $today = Carbon::today();
            if ($statusFilter === 'valid') {
                $certQuery->where('status', 'valid')->where(function ($q) use ($today) {
                    $q->whereNull('expiry_date')->orWhere('expiry_date', '>', $today->copy()->addDays(30));
                });
            } elseif ($statusFilter === 'expiring_soon') {
                $certQuery->where('expiry_date', '>=', $today)
                    ->where('expiry_date', '<=', $today->copy()->addDays(30));
            } elseif ($statusFilter === 'expired') {
                $certQuery->where('expiry_date', '<', $today);
            } elseif ($statusFilter === 'in_training') {
                $certQuery->where('status', 'in_training');
            }
        }

        $certificates = $certQuery->orderBy('expiry_date')->paginate(12)->withQueryString();

        // Training Catalog Query
        $catalogQuery = HcdTraining::withCount('employeeTrainings');
        if ($categoryFilter !== 'all') {
            $catalogQuery->where('category', $categoryFilter);
        }
        $trainings = $catalogQuery->orderBy('title')->get();

        $employees = Employee::with('user')->orderBy('employee_number')->get();
        $categories = ['Safety & K3', 'Technical Specialist', 'Project Management', 'Soft Skills', 'Quality Assurance'];

        return view('pages.hcd.training.index', compact(
            'activeTab',
            'certificates',
            'trainings',
            'counts',
            'employees',
            'categories',
            'statusFilter',
            'categoryFilter',
            'employeeFilter',
            'search'
        ));
    }

    /**
     * Store new training catalog item.
     */
    public function storeCatalog(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'institution' => 'nullable|string|max:150',
            'validity_years' => 'required|integer|min:1|max:10',
            'description' => 'nullable|string',
        ]);

        HcdTraining::create($validated);

        return redirect()->route('dashboard-admin.hcd.training.index', ['tab' => 'catalog'])
            ->with('success', "Program pelatihan '{$validated['title']}' berhasil ditambahkan ke katalog.");
    }

    /**
     * Record a new certificate for an employee.
     */
    public function storeCertificate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'training_id' => 'required|exists:hcd_trainings,id',
            'certificate_number' => 'nullable|string|max:100',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date|after_or_equal:issue_date',
            'cost' => 'nullable|numeric|min:0',
            'status' => 'required|in:valid,in_training',
            'notes' => 'nullable|string',
            'certificate_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $filePath = null;
        if ($request->hasFile('certificate_file')) {
            $file = $request->file('certificate_file');
            $fileName = 'cert_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->move(public_path('uploads/certificates'), $fileName);
            $filePath = 'uploads/certificates/' . $fileName;
        }

        // Auto compute status
        $status = $validated['status'];
        if ($status !== 'in_training' && !empty($validated['expiry_date'])) {
            $exp = Carbon::parse($validated['expiry_date']);
            if ($exp->isPast()) {
                $status = 'expired';
            } elseif ($exp->diffInDays(Carbon::today()) <= 30) {
                $status = 'expiring_soon';
            }
        }

        HcdEmployeeTraining::create([
            'employee_id' => $validated['employee_id'],
            'training_id' => $validated['training_id'],
            'certificate_number' => $validated['certificate_number'] ?? ('CERT-' . strtoupper(uniqid())),
            'issue_date' => $validated['issue_date'],
            'expiry_date' => $validated['expiry_date'] ?? null,
            'cost' => $validated['cost'] ?? 0,
            'status' => $status,
            'certificate_file' => $filePath,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('dashboard-admin.hcd.training.index', ['tab' => 'certificates'])
            ->with('success', "Sertifikat berhasil dicatat untuk karyawan.");
    }

    /**
     * Delete certificate record.
     */
    public function destroyCertificate(HcdEmployeeTraining $certificate): RedirectResponse
    {
        $certificate->delete();

        return redirect()->route('dashboard-admin.hcd.training.index', ['tab' => 'certificates'])
            ->with('success', "Catatan sertifikat telah dihapus.");
    }
}
