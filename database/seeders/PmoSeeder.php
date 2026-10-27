<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\ProjectMember;
use App\Models\ProjectMilestone;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PmoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure required departments & positions
        $pmoDept = Department::firstOrCreate(['code' => 'PMO'], ['name' => 'Project Management (PMO)']);
        $opsDept = Department::firstOrCreate(['code' => 'OPS'], ['name' => 'Operations']);
        $hsseDept = Department::firstOrCreate(['code' => 'HSE'], ['name' => 'HSSE']);

        $posPm = Position::firstOrCreate(['title' => 'Project Manager', 'department_id' => $pmoDept->id], ['level' => 'Manager']);
        $posPe = Position::firstOrCreate(['title' => 'Project Engineer', 'department_id' => $pmoDept->id], ['level' => 'Staff']);
        $posSupervisor = Position::firstOrCreate(['title' => 'Site Supervisor', 'department_id' => $opsDept->id], ['level' => 'Supervisor']);
        $posHsse = Position::firstOrCreate(['title' => 'HSSE Officer', 'department_id' => $hsseDept->id], ['level' => 'Staff']);
        $posQc = Position::firstOrCreate(['title' => 'QA/QC Inspector', 'department_id' => $opsDept->id], ['level' => 'Staff']);

        // Helper to create or get an employee
        $createEmp = function (string $name, string $email, string $code, Position $position, Department $department, string $phone) {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'initials' => strtoupper(substr($name, 0, 2)),
                    'password' => Hash::make('password123'),
                    'is_active' => true,
                ]
            );

            return Employee::firstOrCreate(
                ['employee_number' => $code],
                [
                    'user_id' => $user->id,
                    'department_id' => $department->id,
                    'position_id' => $position->id,
                    'join_date' => '2022-01-15',
                    'phone' => $phone,
                ]
            );
        };

        $pmDimas = $createEmp('Dimas Pratama', 'dimas.pratama@elbema.co.id', 'EMP-PMO-001', $posPm, $pmoDept, '08129988111');
        $pmAndi = $createEmp('Andi Rahman', 'andi.rahman@elbema.co.id', 'EMP-PMO-002', $posPm, $pmoDept, '08129988222');
        $pmNisa = $createEmp('Nisa Ananda', 'nisa.ananda@elbema.co.id', 'EMP-PMO-003', $posPm, $pmoDept, '08129988333');
        $pmRizky = $createEmp('Rizky Saputra', 'rizky.saputra@elbema.co.id', 'EMP-PMO-004', $posPm, $pmoDept, '08129988444');
        $pmBudi = $createEmp('Budi Santoso', 'budi.santoso@elbema.co.id', 'EMP-PMO-005', $posSupervisor, $opsDept, '08129988555');

        $empPe1 = $createEmp('Andi Pratama', 'andi.pe@elbema.co.id', 'EMP-PE-001', $posPe, $pmoDept, '08128877111');
        $empHsse1 = $createEmp('Dimas Saputra', 'dimas.hsse@elbema.co.id', 'EMP-HSE-001', $posHsse, $hsseDept, '08128877222');
        $empHsse2 = $createEmp('Rina Maharani', 'rina.m@elbema.co.id', 'EMP-HSE-002', $posHsse, $hsseDept, '08128877333');
        $empQc1 = $createEmp('Fajar Hidayat', 'fajar.qc@elbema.co.id', 'EMP-QC-001', $posQc, $opsDept, '08128877444');

        // 2. Customers helper
        $custMap = [
            'pusri' => Customer::firstOrCreate(['company_name' => 'PT Pupuk Sriwidjaja (Palembang)'], ['location' => 'Palembang', 'industry' => 'Fertilizer & Chemicals', 'pic_name' => 'Hendra Wijaya', 'pic_phone' => '08122222333', 'pic_email' => 'hendra.w@pusri.co.id']),
            'kpi' => Customer::firstOrCreate(['company_name' => 'PT Kaltim Parna Industri (Bontang)'], ['location' => 'Bontang', 'industry' => 'Industrial Chemicals', 'pic_name' => 'Ir. Bambang S.', 'pic_phone' => '08121111222', 'pic_email' => 'bambang.s@kpi.co.id']),
            'vale' => Customer::firstOrCreate(['company_name' => 'PT Vale Indonesia (Sorowako)'], ['location' => 'Sorowako', 'industry' => 'Mining & Minerals', 'pic_name' => 'Michael Tan', 'pic_phone' => '08123333444', 'pic_email' => 'michael.tan@vale.com']),
            'pertamina' => Customer::firstOrCreate(['company_name' => 'PT Kilang Pertamina RU VII (Kasim)'], ['location' => 'Kasim', 'industry' => 'Oil & Gas', 'pic_name' => 'Agus Priyono', 'pic_phone' => '08126666777', 'pic_email' => 'agus.p@pertamina.com']),
            'antam' => Customer::firstOrCreate(['company_name' => 'PT Antam Pomalaa (Kolaka)'], ['location' => 'Pomalaa', 'industry' => 'Metals & Mining', 'pic_name' => 'Bambang Irawan', 'pic_phone' => '08127777888', 'pic_email' => 'bambang.i@antam.com']),
            'pkt' => Customer::firstOrCreate(['company_name' => 'PT Pupuk Kalimantan Timur (Bontang)'], ['location' => 'Bontang', 'industry' => 'Fertilizer & Chemicals', 'pic_name' => 'Achmad Fauzi', 'pic_phone' => '08124444555', 'pic_email' => 'fauzi.a@pupukkaltim.com']),
            'ecogreen' => Customer::firstOrCreate(['company_name' => 'PT Ecogreen Oleochemicals (Batam)'], ['location' => 'Batam', 'industry' => 'Oleochemicals', 'pic_name' => 'Rahmat Hidayat', 'pic_phone' => '08128888999', 'pic_email' => 'rahmat.h@ecogreen.com']),
            'wilmar' => Customer::firstOrCreate(['company_name' => 'PT Wilmar Nabati Indonesia (Gresik)'], ['location' => 'Gresik', 'industry' => 'Industrial Chemicals', 'pic_name' => 'Denny Setiawan', 'pic_phone' => '08129999000', 'pic_email' => 'denny.s@wilmar.co.id']),
        ];

        // 3. Realistic Projects data
        $projectsData = [
            [
                'project_code' => 'SO-26-001',
                'title' => 'OLSS Ammonia Converter',
                'customer' => $custMap['pusri'],
                'manager' => $pmDimas,
                'location' => 'Palembang',
                'category' => 'Online Leak Sealing',
                'sector' => 'Fertilizer & Chemicals',
                'procurement_type' => 'Open Tender',
                'contract_value' => 2500000000.00,
                'budget' => 2180000000.00,
                'actual_cost' => 1150000000.00,
                'progress_percentage' => 48,
                'planned_progress_percentage' => 65,
                'start_date' => '2026-09-01',
                'end_date' => '2026-11-15',
                'status' => Project::STATUS_ON_PROGRESS,
                'risk_level' => Project::RISK_HIGH,
                'readiness_percentage' => 74,
                'quality_percentage' => 82,
                'hsse_percentage' => 94,
                'man_hours' => 2480,
                'invoice_submitted' => 1650000000.00,
                'invoice_paid' => 850000000.00,
                'notes' => 'Next milestone: Clamp installation & injection target 22 Okt 2026.',
                'milestones' => [
                    ['code' => 'WBS-01', 'title' => 'Engineering & Clamp Design Validation', 'weight' => 20, 'progress' => 100, 'status' => 'completed', 'target' => '2026-09-15', 'actual' => '2026-09-14'],
                    ['code' => 'WBS-02', 'title' => 'Material Procurement & Workshop Fabrication', 'weight' => 25, 'progress' => 100, 'status' => 'completed', 'target' => '2026-09-30', 'actual' => '2026-09-29'],
                    ['code' => 'WBS-03', 'title' => 'Site Mobilization & Hot Permit Verification', 'weight' => 15, 'progress' => 100, 'status' => 'completed', 'target' => '2026-10-10', 'actual' => '2026-10-10'],
                    ['code' => 'WBS-04', 'title' => 'Clamp Installation & Sealing Compound Injection', 'weight' => 30, 'progress' => 40, 'status' => 'in_progress', 'target' => '2026-10-25', 'actual' => null],
                    ['code' => 'WBS-05', 'title' => 'Final Pressure Test, Dossier & Handover', 'weight' => 10, 'progress' => 0, 'status' => 'pending', 'target' => '2026-11-15', 'actual' => null],
                ],
                'members' => [
                    ['emp' => $empPe1, 'role' => 'Lead Project Engineer', 'discipline' => 'Mechanical / Leak Sealing', 'rating' => 4.6, 'notes' => 'Koordinasi teknis fabrikasi dan approval clamp berjalan sangat baik.'],
                    ['emp' => $empHsse1, 'role' => 'Safety Officer', 'discipline' => 'HSSE', 'rating' => 4.8, 'notes' => 'Pengawasan permit confined space dan gas monitoring konsisten.'],
                ],
            ],
            [
                'project_code' => 'SO-26-002',
                'title' => 'CO₂ Spherical Tank Cleaning',
                'customer' => $custMap['kpi'],
                'manager' => $pmAndi,
                'location' => 'Bontang',
                'category' => 'Tank Cleaning',
                'sector' => 'Industrial Chemicals',
                'procurement_type' => 'Direct Selection',
                'contract_value' => 3800000000.00,
                'budget' => 3120000000.00,
                'actual_cost' => 3040000000.00,
                'progress_percentage' => 100,
                'planned_progress_percentage' => 100,
                'start_date' => '2026-07-01',
                'end_date' => '2026-10-10',
                'status' => Project::STATUS_COMPLETED,
                'risk_level' => Project::RISK_LOW,
                'readiness_percentage' => 100,
                'quality_percentage' => 96,
                'hsse_percentage' => 100,
                'man_hours' => 3120,
                'invoice_submitted' => 3800000000.00,
                'invoice_paid' => 3100000000.00,
                'notes' => 'Pekerjaan selesai 100%. Handover dan BASTP telah diterbitkan.',
                'milestones' => [
                    ['code' => 'WBS-01', 'title' => 'Scaffolding & Tank Isolation Procedure', 'weight' => 25, 'progress' => 100, 'status' => 'completed', 'target' => '2026-07-20', 'actual' => '2026-07-18'],
                    ['code' => 'WBS-02', 'title' => 'High Pressure Water Jetting & Sludge Removal', 'weight' => 40, 'progress' => 100, 'status' => 'completed', 'target' => '2026-08-30', 'actual' => '2026-08-28'],
                    ['code' => 'WBS-03', 'title' => 'Internal Inspection & Gas Free Certification', 'weight' => 20, 'progress' => 100, 'status' => 'completed', 'target' => '2026-09-20', 'actual' => '2026-09-18'],
                    ['code' => 'WBS-04', 'title' => 'Final Dossier, Demob & Client BASTP', 'weight' => 15, 'progress' => 100, 'status' => 'completed', 'target' => '2026-10-10', 'actual' => '2026-10-10'],
                ],
                'members' => [
                    ['emp' => $empPe1, 'role' => 'Project Manager Site', 'discipline' => 'Operations', 'rating' => 4.8, 'notes' => 'Memimpin penyelesaian pekerjaan tank cleaning tepat waktu dan zero incident.'],
                    ['emp' => $pmBudi, 'role' => 'Mechanical Supervisor', 'discipline' => 'Mechanical Maintenance', 'rating' => 4.5, 'notes' => 'Menjaga mutu dan produktivitas tim lapangan saat turnaround.'],
                ],
            ],
            [
                'project_code' => 'SO-26-003',
                'title' => 'HE Cleaning & Inspection',
                'customer' => $custMap['vale'],
                'manager' => $pmNisa,
                'location' => 'Sorowako',
                'category' => 'Heat Exchanger Cleaning',
                'sector' => 'Mining & Minerals',
                'procurement_type' => 'Direct Appointment',
                'contract_value' => 1100000000.00,
                'budget' => 890000000.00,
                'actual_cost' => 260000000.00,
                'progress_percentage' => 22,
                'planned_progress_percentage' => 24,
                'start_date' => '2026-10-01',
                'end_date' => '2026-12-20',
                'status' => Project::STATUS_ON_PROGRESS,
                'risk_level' => Project::RISK_MEDIUM,
                'readiness_percentage' => 61,
                'quality_percentage' => 88,
                'hsse_percentage' => 100,
                'man_hours' => 980,
                'invoice_submitted' => 330000000.00,
                'invoice_paid' => 0.00,
                'notes' => 'Mobilization dan sertifikasi jetting pump 10.000 PSI.',
                'milestones' => [
                    ['code' => 'WBS-01', 'title' => 'Mobilization & Equipment Readiness Inspection', 'weight' => 20, 'progress' => 100, 'status' => 'completed', 'target' => '2026-10-15', 'actual' => '2026-10-14'],
                    ['code' => 'WBS-02', 'title' => 'Bundle Pulling & Initial Ultrasonic Assessment', 'weight' => 25, 'progress' => 20, 'status' => 'in_progress', 'target' => '2026-10-31', 'actual' => null],
                    ['code' => 'WBS-03', 'title' => 'Hydro Jetting Tube Bundle & Shell Cleaning', 'weight' => 35, 'progress' => 0, 'status' => 'pending', 'target' => '2026-11-25', 'actual' => null],
                    ['code' => 'WBS-04', 'title' => 'Eddy Current Tube Inspection & Box-up', 'weight' => 20, 'progress' => 0, 'status' => 'pending', 'target' => '2026-12-20', 'actual' => null],
                ],
                'members' => [
                    ['emp' => $empHsse2, 'role' => 'HSSE Officer', 'discipline' => 'Health & Safety', 'rating' => 4.6, 'notes' => 'Proaktif mengawal kepatuhan HSSE di area kerja Sorowako.'],
                ],
            ],
            [
                'project_code' => 'SO-26-004',
                'title' => 'Fin Fan Cooler Maintenance',
                'customer' => $custMap['pertamina'],
                'manager' => $pmDimas,
                'location' => 'Kasim',
                'category' => 'Maintenance & Repair',
                'sector' => 'Oil & Gas',
                'procurement_type' => 'Open Tender',
                'contract_value' => 1800000000.00,
                'budget' => 1490000000.00,
                'actual_cost' => 1440000000.00,
                'progress_percentage' => 100,
                'planned_progress_percentage' => 100,
                'start_date' => '2026-05-10',
                'end_date' => '2026-08-15',
                'status' => Project::STATUS_COMPLETED,
                'risk_level' => Project::RISK_LOW,
                'readiness_percentage' => 100,
                'quality_percentage' => 98,
                'hsse_percentage' => 100,
                'man_hours' => 1840,
                'invoice_submitted' => 1800000000.00,
                'invoice_paid' => 1800000000.00,
                'notes' => 'Proyek closed dengan margin positif dan repeat order potensional.',
                'milestones' => [
                    ['code' => 'WBS-01', 'title' => 'Pre-shutdown Inspection & Vibration Check', 'weight' => 20, 'progress' => 100, 'status' => 'completed', 'target' => '2026-05-25', 'actual' => '2026-05-24'],
                    ['code' => 'WBS-02', 'title' => 'Blade Pitch Adjustment & Bearing Overhaul', 'weight' => 50, 'progress' => 100, 'status' => 'completed', 'target' => '2026-07-15', 'actual' => '2026-07-12'],
                    ['code' => 'WBS-03', 'title' => 'Performance Test, Commissioning & Handover', 'weight' => 30, 'progress' => 100, 'status' => 'completed', 'target' => '2026-08-15', 'actual' => '2026-08-14'],
                ],
                'members' => [
                    ['emp' => $empHsse1, 'role' => 'HSSE Officer', 'discipline' => 'Health & Safety', 'rating' => 4.7, 'notes' => 'Pengendalian risiko dan toolbox meeting konsisten.'],
                    ['emp' => $pmBudi, 'role' => 'Mechanical Lead', 'discipline' => 'Mechanical Maintenance', 'rating' => 4.5, 'notes' => 'Eksekusi overhaul bearing sesuai prosedur presisi.'],
                ],
            ],
            [
                'project_code' => 'SO-26-005',
                'title' => 'Shutdown Maintenance Package',
                'customer' => $custMap['antam'],
                'manager' => $pmRizky,
                'location' => 'Pomalaa',
                'category' => 'Maintenance & Repair',
                'sector' => 'Metals & Mining',
                'procurement_type' => 'Direct Selection',
                'contract_value' => 3400000000.00,
                'budget' => 2790000000.00,
                'actual_cost' => 1510000000.00,
                'progress_percentage' => 54,
                'planned_progress_percentage' => 58,
                'start_date' => '2026-08-01',
                'end_date' => '2026-12-15',
                'status' => Project::STATUS_ON_PROGRESS,
                'risk_level' => Project::RISK_MEDIUM,
                'readiness_percentage' => 86,
                'quality_percentage' => 91,
                'hsse_percentage' => 97,
                'man_hours' => 2740,
                'invoice_submitted' => 1700000000.00,
                'invoice_paid' => 1050000000.00,
                'notes' => 'Target mechanical completion 05 Nov 2026.',
                'milestones' => [
                    ['code' => 'WBS-01', 'title' => 'Equipment Preparation & Field Survey', 'weight' => 15, 'progress' => 100, 'status' => 'completed', 'target' => '2026-08-15', 'actual' => '2026-08-14'],
                    ['code' => 'WBS-02', 'title' => 'Rotary Kiln Static Seal Replacement', 'weight' => 35, 'progress' => 100, 'status' => 'completed', 'target' => '2026-09-30', 'actual' => '2026-09-28'],
                    ['code' => 'WBS-03', 'title' => 'Conveyor & Crusher Overhaul Package', 'weight' => 30, 'progress' => 45, 'status' => 'in_progress', 'target' => '2026-11-05', 'actual' => null],
                    ['code' => 'WBS-04', 'title' => 'Commissioning, Load Test & BASTP', 'weight' => 20, 'progress' => 0, 'status' => 'pending', 'target' => '2026-12-15', 'actual' => null],
                ],
                'members' => [
                    ['emp' => $empHsse1, 'role' => 'Safety Officer', 'discipline' => 'HSSE', 'rating' => 4.4, 'notes' => 'Pelaporan dan tindak lanjut keselamatan harian berjalan rapi.'],
                    ['emp' => $empHsse2, 'role' => 'HSSE Coordinator', 'discipline' => 'Health & Safety', 'rating' => 4.8, 'notes' => 'Inspeksi lapangan dan mitigasi bahaya ketinggian sangat baik.'],
                ],
            ],
            [
                'project_code' => 'SO-26-006',
                'title' => 'Valve & Pump Supply Package',
                'customer' => $custMap['pkt'],
                'manager' => $pmAndi,
                'location' => 'Bontang',
                'category' => 'Industrial Supply',
                'sector' => 'Fertilizer & Chemicals',
                'procurement_type' => 'Direct Appointment',
                'contract_value' => 2400000000.00,
                'budget' => 2110000000.00,
                'actual_cost' => 1280000000.00,
                'progress_percentage' => 61,
                'planned_progress_percentage' => 63,
                'start_date' => '2026-08-20',
                'end_date' => '2026-12-10',
                'status' => Project::STATUS_ON_PROGRESS,
                'risk_level' => Project::RISK_LOW,
                'readiness_percentage' => 80,
                'quality_percentage' => 90,
                'hsse_percentage' => 100,
                'man_hours' => 1560,
                'invoice_submitted' => 1440000000.00,
                'invoice_paid' => 600000000.00,
                'notes' => 'Vendor delivery & inspection target 30 Okt 2026.',
                'milestones' => [
                    ['code' => 'WBS-01', 'title' => 'PO Issuance & Factory Acceptance Test Plan', 'weight' => 25, 'progress' => 100, 'status' => 'completed', 'target' => '2026-09-10', 'actual' => '2026-09-08'],
                    ['code' => 'WBS-02', 'title' => 'Assembly Inspection at OEM Workshop', 'weight' => 35, 'progress' => 100, 'status' => 'completed', 'target' => '2026-10-10', 'actual' => '2026-10-09'],
                    ['code' => 'WBS-03', 'title' => 'Site Delivery & Hydrostatic Test Verification', 'weight' => 25, 'progress' => 30, 'status' => 'in_progress', 'target' => '2026-11-10', 'actual' => null],
                    ['code' => 'WBS-04', 'title' => 'Final Handover Documentation & Mill Certificates', 'weight' => 15, 'progress' => 0, 'status' => 'pending', 'target' => '2026-12-10', 'actual' => null],
                ],
                'members' => [
                    ['emp' => $empQc1, 'role' => 'QA/QC Inspector', 'discipline' => 'Quality', 'rating' => 4.5, 'notes' => 'Dokumen mill certificate dan traceability valve lengkap.'],
                ],
            ],
            [
                'project_code' => 'SO-26-007',
                'title' => 'FSO Tank Cleaning Campaign',
                'customer' => $custMap['ecogreen'],
                'manager' => $pmDimas,
                'location' => 'Batam',
                'category' => 'Tank Cleaning',
                'sector' => 'Oleochemicals',
                'procurement_type' => 'Open Tender',
                'contract_value' => 2300000000.00,
                'budget' => 1890000000.00,
                'actual_cost' => 1800000000.00,
                'progress_percentage' => 100,
                'planned_progress_percentage' => 100,
                'start_date' => '2026-06-01',
                'end_date' => '2026-09-20',
                'status' => Project::STATUS_COMPLETED,
                'risk_level' => Project::RISK_LOW,
                'readiness_percentage' => 100,
                'quality_percentage' => 95,
                'hsse_percentage' => 100,
                'man_hours' => 2160,
                'invoice_submitted' => 2300000000.00,
                'invoice_paid' => 2050000000.00,
                'notes' => 'Customer acceptance komplit pada 20 September 2026.',
                'milestones' => [
                    ['code' => 'WBS-01', 'title' => 'Marine Gas-Free & Vessel De-ballasting', 'weight' => 30, 'progress' => 100, 'status' => 'completed', 'target' => '2026-06-25', 'actual' => '2026-06-24'],
                    ['code' => 'WBS-02', 'title' => 'Chemical Wash & Descaling of Cargo Tanks', 'weight' => 40, 'progress' => 100, 'status' => 'completed', 'target' => '2026-08-10', 'actual' => '2026-08-08'],
                    ['code' => 'WBS-03', 'title' => 'Wall Wash Test & Class Surveyor Sign-off', 'weight' => 30, 'progress' => 100, 'status' => 'completed', 'target' => '2026-09-20', 'actual' => '2026-09-20'],
                ],
                'members' => [
                    ['emp' => $empPe1, 'role' => 'Project Coordinator', 'discipline' => 'Operations', 'rating' => 4.7, 'notes' => 'Koordinasi marine surveyor dan port authority berjalan lancar.'],
                ],
            ],
            [
                'project_code' => 'SO-26-008',
                'title' => 'Online Leak Sealing – Process Line',
                'customer' => $custMap['wilmar'],
                'manager' => $pmBudi,
                'location' => 'Gresik',
                'category' => 'Online Leak Sealing',
                'sector' => 'Industrial Chemicals',
                'procurement_type' => 'Direct Selection',
                'contract_value' => 1150000000.00,
                'budget' => 920000000.00,
                'actual_cost' => 980000000.00,
                'progress_percentage' => 100,
                'planned_progress_percentage' => 100,
                'start_date' => '2026-04-10',
                'end_date' => '2026-06-30',
                'status' => Project::STATUS_COMPLETED,
                'risk_level' => Project::RISK_LOW,
                'readiness_percentage' => 100,
                'quality_percentage' => 92,
                'hsse_percentage' => 100,
                'man_hours' => 740,
                'invoice_submitted' => 1150000000.00,
                'invoice_paid' => 1150000000.00,
                'notes' => 'Commercial & project closeout completed.',
                'milestones' => [
                    ['code' => 'WBS-01', 'title' => 'Emergency Line Survey & Pressure Calculation', 'weight' => 30, 'progress' => 100, 'status' => 'completed', 'target' => '2026-04-20', 'actual' => '2026-04-19'],
                    ['code' => 'WBS-02', 'title' => 'Enclosure Installation & Live Sealing Injection', 'weight' => 50, 'progress' => 100, 'status' => 'completed', 'target' => '2026-05-20', 'actual' => '2026-05-18'],
                    ['code' => 'WBS-03', 'title' => 'Commercial Settlement & Final Closeout', 'weight' => 20, 'progress' => 100, 'status' => 'completed', 'target' => '2026-06-30', 'actual' => '2026-06-30'],
                ],
                'members' => [
                    ['emp' => $empHsse1, 'role' => 'Safety Officer', 'discipline' => 'HSSE', 'rating' => 4.5, 'notes' => 'Pengawasan permit live steam injection terkendali dengan baik.'],
                ],
            ],
        ];

        foreach ($projectsData as $pData) {
            $project = Project::updateOrCreate(
                ['project_code' => $pData['project_code']],
                [
                    'customer_id' => $pData['customer']->id,
                    'project_manager_id' => $pData['manager']->id,
                    'title' => $pData['title'],
                    'contract_value' => $pData['contract_value'],
                    'budget' => $pData['budget'],
                    'actual_cost' => $pData['actual_cost'],
                    'start_date' => $pData['start_date'],
                    'end_date' => $pData['end_date'],
                    'status' => $pData['status'],
                    'progress_percentage' => $pData['progress_percentage'],
                    'planned_progress_percentage' => $pData['planned_progress_percentage'],
                    'location' => $pData['location'],
                    'category' => $pData['category'],
                    'sector' => $pData['sector'],
                    'procurement_type' => $pData['procurement_type'],
                    'risk_level' => $pData['risk_level'],
                    'readiness_percentage' => $pData['readiness_percentage'],
                    'quality_percentage' => $pData['quality_percentage'],
                    'hsse_percentage' => $pData['hsse_percentage'],
                    'man_hours' => $pData['man_hours'],
                    'invoice_submitted' => $pData['invoice_submitted'],
                    'invoice_paid' => $pData['invoice_paid'],
                    'notes' => $pData['notes'],
                ]
            );

            // Populate Milestones
            foreach ($pData['milestones'] as $m) {
                ProjectMilestone::updateOrCreate(
                    [
                        'project_id' => $project->id,
                        'milestone_code' => $m['code'],
                    ],
                    [
                        'title' => $m['title'],
                        'weight_percentage' => $m['weight'],
                        'progress_percentage' => $m['progress'],
                        'status' => $m['status'],
                        'target_date' => $m['target'],
                        'actual_date' => $m['actual'],
                        'deliverable' => 'Approved Work Dossier / Report',
                    ]
                );
            }

            // Populate Members
            foreach ($pData['members'] as $mem) {
                ProjectMember::updateOrCreate(
                    [
                        'project_id' => $project->id,
                        'employee_id' => $mem['emp']->id,
                    ],
                    [
                        'role' => $mem['role'],
                        'discipline' => $mem['discipline'],
                        'assigned_date' => $pData['start_date'],
                        'rating' => $mem['rating'],
                        'performance_notes' => $mem['notes'],
                        'status' => 'active',
                    ]
                );
            }

            // Populate Sample Document
            ProjectDocument::firstOrCreate(
                [
                    'project_id' => $project->id,
                    'name' => 'Kontrak Pelaksanaan & Scope of Work ('.$project->project_code.')',
                ],
                [
                    'category' => 'Contract',
                    'file_path' => 'assets/documents/sample-contract.pdf',
                    'uploaded_date' => $project->start_date ?? now(),
                    'file_size' => '1.4 MB',
                ]
            );

            // Populate Handover Dossier & Punch List
            $project->update([
                'turnover_dossier_status' => $project->status === 'completed' ? 'Approved' : 'In Review',
                'handover_status' => $project->status === 'completed' ? 'Handed Over' : ($project->risk_level === 'high' ? 'Action Required' : 'In Progress'),
                'punch_list_open_count' => $project->status === 'completed' ? 0 : ($project->risk_level === 'high' ? 6 : 2),
                'certificates_status' => $project->status === 'completed' ? 'All Issued & Approved' : 'PCC Pending / In Review',
            ]);

            // Populate Sample Cost Entries
            $categories = \App\Models\ProjectCostEntry::CATEGORIES;
            $sampleVendors = ['PT Kaltim Daya Mandiri', 'PT Mitra Perkasa Teknik', 'CV Mandiri Sejahtera', 'PT Samator Gas Industri', 'PT Global Safety Gear'];

            for ($i = 0; $i < 3; $i++) {
                $cat = $categories[$i % count($categories)];
                \App\Models\ProjectCostEntry::firstOrCreate(
                    [
                        'project_id' => $project->id,
                        'reference_no' => 'INV-2026-'.$project->id.'0'.$i,
                    ],
                    [
                        'entry_date' => date('Y-m-d', strtotime('-'.($i * 10).' days')),
                        'cost_category' => $cat,
                        'cost_subcategory' => 'Pengeluaran operasional '.$cat,
                        'type' => 'actual',
                        'amount' => 35000000 + ($i * 15000000),
                        'vendor' => $sampleVendors[$i % count($sampleVendors)],
                        'notes' => 'Alokasi pengeluaran riil project lapangan',
                    ]
                );
            }

            \App\Models\ProjectCostEntry::firstOrCreate(
                [
                    'project_id' => $project->id,
                    'reference_no' => 'PO-2026-'.$project->id.'99',
                ],
                [
                    'entry_date' => date('Y-m-d'),
                    'cost_category' => $categories[3],
                    'cost_subcategory' => 'Komitmen Subkontraktor / Jasa Spesialis',
                    'type' => 'commitment',
                    'amount' => 75000000,
                    'vendor' => $sampleVendors[0],
                    'notes' => 'Open Purchase Order vendor pelaksanaan',
                ]
            );
        }
    }
}
