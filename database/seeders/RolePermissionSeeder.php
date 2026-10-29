<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Definition of permissions across all 7 functional departments
        $permissionsByModule = [
            'sales' => [
                'sales.dashboard.view',
                'sales.customer.view',
                'sales.customer.create',
                'sales.customer.edit',
                'sales.customer.delete',
                'sales.opportunity.view_all',
                'sales.opportunity.view_own',
                'sales.opportunity.create',
                'sales.opportunity.edit',
                'sales.opportunity.delete',
                'sales.proposal.view',
                'sales.proposal.create',
                'sales.proposal.edit',
                'sales.proposal.delete',
                'sales.proposal.submit',
                'sales.activity.view',
                'sales.activity.create',
                'sales.activity.edit',
                'sales.activity.delete',
                'sales.follow_up.view',
                'sales.follow_up.create',
                'sales.follow_up.edit',
                'sales.follow_up.delete',
            ],
            'pmo' => [
                'pmo.dashboard.view',
                'pmo.project.view_all',
                'pmo.project.view_own',
                'pmo.project.create',
                'pmo.project.edit',
                'pmo.project.delete',
                'pmo.milestone.manage',
                'pmo.wbs.manage',
                'pmo.team.assign',
            ],
            'finance' => [
                'finance.dashboard.view',
                'finance.budget_rab.view',
                'finance.budget_rab.manage',
                'finance.invoice.view',
                'finance.invoice.issue',
                'finance.vendor_bill.view',
                'finance.vendor_bill.manage',
                'finance.tax.view',
                'finance.tax.manage',
            ],
            'hcd' => [
                'hcd.dashboard.view',
                'hcd.department.manage',
                'hcd.position.manage',
                'hcd.employee.view',
                'hcd.employee.manage',
                'hcd.certification.manage',
                'hcd.mobilization.manage',
                'hcd.task.view',
                'hcd.task.manage',
                'hcd.professional.view',
                'hcd.professional.manage',
            ],
            'hsse' => [
                'hsse.dashboard.view',
                'hsse.permit.view',
                'hsse.permit.issue',
                'hsse.inspection.manage',
                'hsse.incident.report',
            ],
            'procurement' => [
                'procurement.dashboard.view',
                'procurement.vendor.manage',
                'procurement.pr.create',
                'procurement.pr.approve',
                'procurement.po.view',
                'procurement.po.create',
                'procurement.logistic.track',
            ],
            'asset' => [
                'asset.dashboard.view',
                'asset.inventory.view',
                'asset.inventory.manage',
                'asset.allocation.request',
                'asset.allocation.approve',
                'asset.maintenance.schedule',
            ],
        ];

        $allPermissions = [];
        foreach ($permissionsByModule as $modulePermissions) {
            foreach ($modulePermissions as $permissionName) {
                Permission::firstOrCreate(
                    ['name' => $permissionName, 'guard_name' => 'web']
                );
                $allPermissions[] = $permissionName;
            }
        }

        // 2. Define Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $salesDirectorRole = Role::firstOrCreate(['name' => 'sales-director', 'guard_name' => 'web']);
        $salesExecutiveRole = Role::firstOrCreate(['name' => 'sales-executive', 'guard_name' => 'web']);
        $pmoManagerRole = Role::firstOrCreate(['name' => 'pmo-manager', 'guard_name' => 'web']);
        $financeOfficerRole = Role::firstOrCreate(['name' => 'finance-officer', 'guard_name' => 'web']);
        $hcdManagerRole = Role::firstOrCreate(['name' => 'hcd-manager', 'guard_name' => 'web']);
        $hsseOfficerRole = Role::firstOrCreate(['name' => 'hsse-officer', 'guard_name' => 'web']);
        $procurementOfficerRole = Role::firstOrCreate(['name' => 'procurement-officer', 'guard_name' => 'web']);
        $assetManagerRole = Role::firstOrCreate(['name' => 'asset-manager', 'guard_name' => 'web']);

        // Sync Super Admin permissions
        $superAdminRole->syncPermissions(Permission::all());

        // Assign Sales Director permissions
        $salesDirectorRole->syncPermissions([
            ...$permissionsByModule['sales'],
            'pmo.project.view_all',
            'finance.budget_rab.view',
        ]);

        // Assign Sales Executive permissions
        $salesExecutiveRole->syncPermissions([
            'sales.dashboard.view',
            'sales.customer.view',
            'sales.customer.create',
            'sales.customer.edit',
            'sales.opportunity.view_own',
            'sales.opportunity.create',
            'sales.opportunity.edit',
            'sales.proposal.view',
            'sales.proposal.create',
            'sales.proposal.edit',
            'sales.proposal.submit',
            'sales.activity.view',
            'sales.activity.create',
            'sales.activity.edit',
            'sales.activity.delete',
            'sales.follow_up.view',
            'sales.follow_up.create',
            'sales.follow_up.edit',
            'sales.follow_up.delete',
        ]);

        // Assign PMO Manager permissions
        $pmoManagerRole->syncPermissions([
            ...$permissionsByModule['pmo'],
            'sales.opportunity.view_all',
            'sales.proposal.view',
            'asset.allocation.request',
            'procurement.pr.create',
        ]);

        // Assign Finance Officer permissions
        $financeOfficerRole->syncPermissions([
            ...$permissionsByModule['finance'],
            'pmo.project.view_all',
            'procurement.po.view',
        ]);

        // Assign HCD Manager permissions
        $hcdManagerRole->syncPermissions([
            ...$permissionsByModule['hcd'],
        ]);

        // Assign HSSE Officer permissions
        $hsseOfficerRole->syncPermissions([
            ...$permissionsByModule['hsse'],
            'pmo.project.view_all',
        ]);

        // Assign Procurement Officer permissions
        $procurementOfficerRole->syncPermissions([
            ...$permissionsByModule['procurement'],
            'pmo.project.view_all',
        ]);

        // Assign Asset Manager permissions
        $assetManagerRole->syncPermissions([
            ...$permissionsByModule['asset'],
            'pmo.project.view_all',
        ]);

        // 3. Create HCD Structural Foundation (Departments & Positions)
        $deptSales = Department::firstOrCreate(
            ['code' => 'SALES'],
            ['name' => 'Sales & Business Development', 'description' => 'Sales, Tendering, and Customer Relationship']
        );

        $deptPmo = Department::firstOrCreate(
            ['code' => 'PMO'],
            ['name' => 'Project Management Office', 'description' => 'Project Planning, Execution, and Field Operations']
        );

        $deptFin = Department::firstOrCreate(
            ['code' => 'FIN'],
            ['name' => 'Finance & Accounting', 'description' => 'Financial Control, Taxation, and Cashflow']
        );

        $deptHcd = Department::firstOrCreate(
            ['code' => 'HCD'],
            ['name' => 'Human Capital Development', 'description' => 'People, Competency, and Organizational Development']
        );

        $deptHsse = Department::firstOrCreate(
            ['code' => 'HSSE'],
            ['name' => 'Health, Safety, Security & Environment', 'description' => 'Site Safety, Risk Management, and JSA']
        );

        $deptProc = Department::firstOrCreate(
            ['code' => 'PROC'],
            ['name' => 'Procurement & Logistics', 'description' => 'Vendor Management and Material Supply']
        );

        $deptAsset = Department::firstOrCreate(
            ['code' => 'ASSET'],
            ['name' => 'Asset & Equipment Management', 'description' => 'Equipment Inventory, Calibration, and Maintenance']
        );

        $posSalesDirector = Position::firstOrCreate(
            ['department_id' => $deptSales->id, 'title' => 'Sales Director'],
            ['level' => 'Director']
        );

        $posSalesExecutive = Position::firstOrCreate(
            ['department_id' => $deptSales->id, 'title' => 'Sales Executive'],
            ['level' => 'Executive']
        );

        $posProjectManager = Position::firstOrCreate(
            ['department_id' => $deptPmo->id, 'title' => 'Senior Project Manager'],
            ['level' => 'Manager']
        );

        $posFinanceOfficer = Position::firstOrCreate(
            ['department_id' => $deptFin->id, 'title' => 'Finance & Tax Officer'],
            ['level' => 'Officer']
        );

        $posHcdManager = Position::firstOrCreate(
            ['department_id' => $deptHcd->id, 'title' => 'Human Capital Manager'],
            ['level' => 'Manager']
        );

        $posHsseOfficer = Position::firstOrCreate(
            ['department_id' => $deptHsse->id, 'title' => 'HSSE Officer'],
            ['level' => 'Officer']
        );

        $posProcurementOfficer = Position::firstOrCreate(
            ['department_id' => $deptProc->id, 'title' => 'Procurement & Logistics Officer'],
            ['level' => 'Officer']
        );

        $posAssetManager = Position::firstOrCreate(
            ['department_id' => $deptAsset->id, 'title' => 'Asset & Equipment Manager'],
            ['level' => 'Manager']
        );

        // 4. Create Simulated Users & Associated Employee Records for ALL Roles
        $simulatedStaff = [
            [
                'name' => 'Juliyanto',
                'email' => 'juliyanto@elbema.co.id',
                'initials' => 'JS',
                'role' => 'sales-director',
                'position' => $posSalesDirector,
                'department' => $deptSales,
                'emp_number' => 'EMP-SLS-001',
                'join_date' => '2020-01-15',
                'phone' => '081122334455',
            ],
            [
                'name' => 'Andi R.',
                'email' => 'andi.r@elbema.co.id',
                'initials' => 'AR',
                'role' => 'sales-executive',
                'position' => $posSalesExecutive,
                'department' => $deptSales,
                'emp_number' => 'EMP-SLS-002',
                'join_date' => '2022-03-01',
                'phone' => '081234567801',
            ],
            [
                'name' => 'Dimas P.',
                'email' => 'dimas.p@elbema.co.id',
                'initials' => 'DP',
                'role' => 'sales-executive',
                'position' => $posSalesExecutive,
                'department' => $deptSales,
                'emp_number' => 'EMP-SLS-003',
                'join_date' => '2023-01-10',
                'phone' => '081234567802',
            ],
            [
                'name' => 'Nisa A.',
                'email' => 'nisa.a@elbema.co.id',
                'initials' => 'NA',
                'role' => 'sales-executive',
                'position' => $posSalesExecutive,
                'department' => $deptSales,
                'emp_number' => 'EMP-SLS-004',
                'join_date' => '2023-06-15',
                'phone' => '081234567803',
            ],
            [
                'name' => 'Ir. Hendro Subagio',
                'email' => 'hendro.pmo@elbema.co.id',
                'initials' => 'HS',
                'role' => 'pmo-manager',
                'position' => $posProjectManager,
                'department' => $deptPmo,
                'emp_number' => 'EMP-PMO-001',
                'join_date' => '2021-05-10',
                'phone' => '081399887766',
            ],
            [
                'name' => 'Bambang Wijaya, S.E.',
                'email' => 'bambang.finance@elbema.co.id',
                'initials' => 'BW',
                'role' => 'finance-officer',
                'position' => $posFinanceOfficer,
                'department' => $deptFin,
                'emp_number' => 'EMP-FIN-001',
                'join_date' => '2021-08-01',
                'phone' => '081255443322',
            ],
            [
                'name' => 'Siti Rahmawati, S.Psi',
                'email' => 'siti.rahma@elbema.co.id',
                'initials' => 'SR',
                'role' => 'hcd-manager',
                'position' => $posHcdManager,
                'department' => $deptHcd,
                'emp_number' => 'EMP-HCD-001',
                'join_date' => '2022-02-14',
                'phone' => '081344556677',
            ],
            [
                'name' => 'Rahmat Pratama, S.T.',
                'email' => 'rahmat.hse@elbema.co.id',
                'initials' => 'RP',
                'role' => 'hsse-officer',
                'position' => $posHsseOfficer,
                'department' => $deptHsse,
                'emp_number' => 'EMP-HSE-001',
                'join_date' => '2021-11-20',
                'phone' => '081277889900',
            ],
            [
                'name' => 'Farhan Kurniawan',
                'email' => 'farhan.proc@elbema.co.id',
                'initials' => 'FK',
                'role' => 'procurement-officer',
                'position' => $posProcurementOfficer,
                'department' => $deptProc,
                'emp_number' => 'EMP-PRC-001',
                'join_date' => '2022-09-05',
                'phone' => '081366778899',
            ],
            [
                'name' => 'Agus Hermanto',
                'email' => 'agus.asset@elbema.co.id',
                'initials' => 'AH',
                'role' => 'asset-manager',
                'position' => $posAssetManager,
                'department' => $deptAsset,
                'emp_number' => 'EMP-AST-001',
                'join_date' => '2022-04-12',
                'phone' => '081288990011',
            ],
        ];

        foreach ($simulatedStaff as $staff) {
            $user = User::firstOrCreate(
                ['email' => $staff['email']],
                [
                    'name' => $staff['name'],
                    'initials' => $staff['initials'],
                    'password' => Hash::make('password123'),
                    'is_active' => true,
                ]
            );

            $user->update([
                'name' => $staff['name'],
                'initials' => $staff['initials'],
                'is_active' => true,
            ]);

            $user->syncRoles([$staff['role']]);

            $existingEmp = Employee::where('user_id', $user->id)->first();
            $empNumber = $existingEmp?->employee_number ?? $staff['emp_number'];

            Employee::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'department_id' => $staff['department']->id,
                    'position_id' => $staff['position']->id,
                    'employee_number' => $empNumber,
                    'join_date' => $staff['join_date'],
                    'phone' => $staff['phone'],
                ]
            );
        }

        // Ensure super-admin account exists and has role
        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@elbema.co.id'],
            [
                'name' => 'Super Administrator',
                'initials' => 'SA',
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]
        );
        $superAdminUser->syncRoles([$superAdminRole]);

        // Auto-assign roles to any other existing users in the database
        $extraRoleMappings = [
            'dimas.hsse@elbema.co.id' => 'hsse-officer',
            'rina.m@elbema.co.id' => 'hsse-officer',
            'dimas.pratama@elbema.co.id' => 'pmo-manager',
            'andi.rahman@elbema.co.id' => 'pmo-manager',
            'nisa.ananda@elbema.co.id' => 'pmo-manager',
            'rizky.saputra@elbema.co.id' => 'pmo-manager',
            'budi.santoso@elbema.co.id' => 'pmo-manager',
            'andi.pe@elbema.co.id' => 'pmo-manager',
            'fajar.qc@elbema.co.id' => 'pmo-manager',
            'joko.hydro@elbema.co.id' => 'asset-manager',
            'agus.torque@elbema.co.id' => 'asset-manager',
        ];

        foreach ($extraRoleMappings as $email => $roleName) {
            $user = User::where('email', $email)->first();
            if ($user && $user->roles()->count() === 0) {
                $user->syncRoles([$roleName]);
            }
        }
    }
}
