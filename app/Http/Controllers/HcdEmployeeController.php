<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class HcdEmployeeController extends Controller
{
    /**
     * Display Employee Register.
     */
    public function index(Request $request): View
    {
        $deptFilter = $request->query('department_id', 'all');
        $statusFilter = $request->query('status', 'all');
        $locationFilter = $request->query('location', 'all');
        $employmentFilter = $request->query('employment_status', 'all');
        $search = $request->query('search');

        $query = Employee::with(['user', 'department', 'position', 'employeeTrainings']);

        if ($deptFilter !== 'all') {
            $query->where('department_id', $deptFilter);
        }

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($locationFilter !== 'all') {
            $query->where('work_location', $locationFilter);
        }

        if ($employmentFilter !== 'all') {
            $query->where('employment_status', $employmentFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('employee_number', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $allEmployees = Employee::all();
        $counts = [
            'all' => $allEmployees->count(),
            'permanent' => $allEmployees->where('employment_status', 'permanent')->count(),
            'contract' => $allEmployees->where('employment_status', 'contract')->count(),
            'active' => $allEmployees->where('status', 'active')->count(),
            'site' => $allEmployees->filter(fn ($e) => str_contains(strtolower($e->work_location ?? ''), 'site'))->count(),
        ];

        $employees = $query->orderBy('employee_number')->paginate(12)->withQueryString();
        $departments = Department::orderBy('name')->get();
        $positions = Position::orderBy('title')->get();

        // Locations list for filter
        $locations = Employee::select('work_location')
            ->whereNotNull('work_location')
            ->distinct()
            ->pluck('work_location');

        return view('pages.hcd.employees.index', compact(
            'employees',
            'counts',
            'departments',
            'positions',
            'locations',
            'deptFilter',
            'statusFilter',
            'locationFilter',
            'employmentFilter',
            'search'
        ));
    }

    /**
     * Show detailed 360 profile of an employee.
     */
    public function show(Employee $employee): View
    {
        $employee->load([
            'user',
            'department',
            'position',
            'managedProjects.customer',
            'tasks.project',
            'employeeTrainings.training',
        ]);

        return view('pages.hcd.employees.show', compact('employee'));
    }

    /**
     * Store a newly created employee.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:6',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'employee_number' => 'nullable|string|unique:employees,employee_number',
            'nik' => 'nullable|string|max:30',
            'employment_status' => 'required|in:permanent,contract,freelance,internship',
            'work_location' => 'required|string|max:100',
            'daily_rate' => 'nullable|numeric|min:0',
            'phone' => 'nullable|string|max:30',
            'join_date' => 'required|date',
            'skills' => 'nullable|string',
            'education' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string',
            'emergency_contact_phone' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password'] ?? 'password123'),
                'initials' => strtoupper(substr($validated['name'], 0, 2)),
                'is_active' => true,
            ]);

            $empCode = $validated['employee_number'] ?? ('EMP-' . date('Y') . '-' . str_pad((string) (Employee::count() + 1), 3, '0', STR_PAD_LEFT));

            Employee::create([
                'user_id' => $user->id,
                'department_id' => $validated['department_id'],
                'position_id' => $validated['position_id'],
                'employee_number' => $empCode,
                'nik' => $validated['nik'] ?? null,
                'employment_status' => $validated['employment_status'],
                'work_location' => $validated['work_location'],
                'status' => 'active',
                'daily_rate' => $validated['daily_rate'] ?? 0,
                'phone' => $validated['phone'] ?? null,
                'join_date' => $validated['join_date'],
                'skills' => $validated['skills'] ?? null,
                'education' => $validated['education'] ?? null,
                'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
            ]);
        });

        return redirect()->route('dashboard-admin.hcd.employees.index')
            ->with('success', "Karyawan {$validated['name']} berhasil didaftarkan ke sistem.");
    }

    /**
     * Update employee profile.
     */
    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'nik' => 'nullable|string|max:30',
            'employment_status' => 'required|in:permanent,contract,freelance,internship',
            'work_location' => 'required|string|max:100',
            'status' => 'required|in:active,inactive,on_leave,mobilized',
            'daily_rate' => 'nullable|numeric|min:0',
            'phone' => 'nullable|string|max:30',
            'skills' => 'nullable|string',
            'education' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string',
            'emergency_contact_phone' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $employee) {
            if ($employee->user) {
                $employee->user->update([
                    'name' => $validated['name'],
                    'initials' => strtoupper(substr($validated['name'], 0, 2)),
                ]);
            }

            $employee->update([
                'department_id' => $validated['department_id'],
                'position_id' => $validated['position_id'],
                'nik' => $validated['nik'] ?? null,
                'employment_status' => $validated['employment_status'],
                'work_location' => $validated['work_location'],
                'status' => $validated['status'],
                'daily_rate' => $validated['daily_rate'] ?? 0,
                'phone' => $validated['phone'] ?? null,
                'skills' => $validated['skills'] ?? null,
                'education' => $validated['education'] ?? null,
                'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
            ]);
        });

        return redirect()->back()
            ->with('success', "Data karyawan {$employee->name} berhasil diperbarui.");
    }

    /**
     * Delete employee record.
     */
    public function destroy(Employee $employee): RedirectResponse
    {
        $name = $employee->name;
        $employee->delete();

        return redirect()->route('dashboard-admin.hcd.employees.index')
            ->with('success', "Karyawan {$name} telah dihapus dari sistem.");
    }
}
