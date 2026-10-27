<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\HcdEmployeeTraining;
use App\Models\HcdProfessional;
use App\Models\HcdTask;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HcdDashboardController extends Controller
{
    /**
     * Display the HCD Executive Dashboard.
     */
    public function index(Request $request): View
    {
        // 1. KPI Metrik Utama
        $allEmployees = Employee::with(['user', 'department', 'position'])->get();
        $totalEmployees = $allEmployees->count();
        $permanentCount = $allEmployees->where('employment_status', 'permanent')->count();
        $contractCount = $allEmployees->where('employment_status', 'contract')->count();

        // Site Crew vs Head Office
        $siteLocations = ['Site Bontang (KPI)', 'Site Palembang (Pusri)', 'Site Sorowako (Vale)', 'Site Bontang', 'Site Palembang', 'Site Sorowako'];
        $siteCrewCount = $allEmployees->filter(function ($emp) {
            return str_contains(strtolower($emp->work_location ?? ''), 'site');
        })->count();
        $headOfficeCount = max(0, $totalEmployees - $siteCrewCount);

        // 2. Sertifikasi Metrik
        $allCertifications = HcdEmployeeTraining::with(['employee.user', 'training'])->get();
        $totalCerts = $allCertifications->count();
        $validCerts = $allCertifications->filter(fn ($c) => $c->computed_status === HcdEmployeeTraining::STATUS_VALID)->count();
        $expiringCerts = $allCertifications->filter(fn ($c) => $c->computed_status === HcdEmployeeTraining::STATUS_EXPIRING_SOON);
        $expiredCerts = $allCertifications->filter(fn ($c) => $c->computed_status === HcdEmployeeTraining::STATUS_EXPIRED);

        // 3. Task Monitoring Metrik
        $allTasks = HcdTask::with(['assignee.user', 'project', 'department'])->get();
        $totalTasks = $allTasks->count();
        $completedTasks = $allTasks->where('status', HcdTask::STATUS_COMPLETED)->count();
        $inProgressTasks = $allTasks->where('status', HcdTask::STATUS_IN_PROGRESS)->count();
        $pendingTasks = $allTasks->where('status', HcdTask::STATUS_PENDING)->count();
        $overdueTasks = $allTasks->filter(fn ($t) => $t->is_overdue)->count();
        $taskCompletionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

        // 4. Talent Pool Professional Database Metrik
        $allProfessionals = HcdProfessional::all();
        $totalProfessionals = $allProfessionals->count();
        $availableProfessionals = $allProfessionals->where('availability_status', HcdProfessional::AVAILABILITY_AVAILABLE)->count();
        $mobilizedProfessionals = $allProfessionals->where('availability_status', HcdProfessional::AVAILABILITY_MOBILIZED)->count();

        // 5. Department Breakdown
        $departments = Department::withCount('employees')->orderByDesc('employees_count')->get();

        // 6. Alert: Sertifikasi Perlu Perhatian Segera (Expiring < 30 Hari atau Expired)
        $urgentCertifications = $allCertifications
            ->filter(fn ($c) => in_array($c->computed_status, [HcdEmployeeTraining::STATUS_EXPIRING_SOON, HcdEmployeeTraining::STATUS_EXPIRED]))
            ->sortBy('expiry_date')
            ->take(6);

        // 7. Recent Tasks List
        $recentTasks = $allTasks->sortByDesc('created_at')->take(5);

        // 8. Projects with assigned field staff
        $activeProjects = Project::with(['manager', 'customer'])
            ->whereIn('status', ['In Progress', 'Active', 'Execution'])
            ->take(4)
            ->get();

        return view('pages.hcd.dashboard.index', compact(
            'totalEmployees',
            'permanentCount',
            'contractCount',
            'siteCrewCount',
            'headOfficeCount',
            'totalCerts',
            'validCerts',
            'expiringCerts',
            'expiredCerts',
            'totalTasks',
            'completedTasks',
            'inProgressTasks',
            'pendingTasks',
            'overdueTasks',
            'taskCompletionRate',
            'totalProfessionals',
            'availableProfessionals',
            'mobilizedProfessionals',
            'departments',
            'urgentCertifications',
            'recentTasks',
            'activeProjects'
        ));
    }
}
