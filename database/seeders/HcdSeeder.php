<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\HcdEmployeeTraining;
use App\Models\HcdProfessional;
use App\Models\HcdTask;
use App\Models\HcdTraining;
use App\Models\Position;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HcdSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Departments & Positions
        $hcdDept = Department::firstOrCreate(['code' => 'HCD'], ['name' => 'Human Capital & Development']);
        $pmoDept = Department::firstOrCreate(['code' => 'PMO'], ['name' => 'Project Management (PMO)']);
        $opsDept = Department::firstOrCreate(['code' => 'OPS'], ['name' => 'Field Operations & Maintenance']);
        $hsseDept = Department::firstOrCreate(['code' => 'HSE'], ['name' => 'Health, Safety & Environment']);
        $salesDept = Department::firstOrCreate(['code' => 'SLS'], ['name' => 'Commercial & Sales']);

        $posHcdManager = Position::firstOrCreate(['title' => 'HCD Manager', 'department_id' => $hcdDept->id], ['level' => 'Manager']);
        $posHrSpecialist = Position::firstOrCreate(['title' => 'HR Operations & Payroll', 'department_id' => $hcdDept->id], ['level' => 'Staff']);
        $posLeadEngineer = Position::firstOrCreate(['title' => 'Lead Project Engineer', 'department_id' => $pmoDept->id], ['level' => 'Senior Staff']);
        $posHydroTech = Position::firstOrCreate(['title' => 'Senior Hydroblasting Technician', 'department_id' => $opsDept->id], ['level' => 'Technician']);
        $posBoltTech = Position::firstOrCreate(['title' => 'Bolt Tensioning & Torquing Tech', 'department_id' => $opsDept->id], ['level' => 'Technician']);
        $posHseInspector = Position::firstOrCreate(['title' => 'Senior HSSE Site Officer', 'department_id' => $hsseDept->id], ['level' => 'Supervisor']);

        // 2. Helper to ensure Employee with full profile
        $ensureEmployee = function (string $name, string $email, string $code, Position $position, Department $department, array $extra = []) {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'initials' => strtoupper(substr($name, 0, 2)),
                    'password' => Hash::make('password123'),
                    'is_active' => true,
                ]
            );

            return Employee::updateOrCreate(
                ['user_id' => $user->id],
                array_merge([
                    'department_id' => $department->id,
                    'position_id' => $position->id,
                    'employee_number' => $code,
                    'join_date' => '2023-01-10',
                    'phone' => '0812' . rand(10000000, 99999999),
                    'nik' => '3271' . rand(100000000000, 999999999999),
                    'employment_status' => 'permanent',
                    'work_location' => 'Head Office',
                    'status' => 'active',
                    'daily_rate' => 650000,
                    'skills' => 'Contract Administration, Indonesian Labor Law, ERP System',
                    'education' => 'S1 Manajemen / Hukum',
                ], $extra)
            );
        };

        $empHcd = $ensureEmployee('Siti Rahmawati, S.Psi', 'siti.rahma@elbema.co.id', 'EMP-HCD-001', $posHcdManager, $hcdDept, [
            'employment_status' => 'permanent',
            'work_location' => 'Head Office (Jakarta)',
            'skills' => 'HR Strategy, Talent Acquisition, People Development, Industrial Relations',
            'education' => 'S1 Psikologi UI',
            'daily_rate' => 850000,
        ]);

        $empOps1 = $ensureEmployee('Joko Prasetyo', 'joko.hydro@elbema.co.id', 'EMP-OPS-011', $posHydroTech, $opsDept, [
            'employment_status' => 'permanent',
            'work_location' => 'Site Bontang (KPI)',
            'skills' => 'UHP Hydroblasting 40.000 PSI, Waterjetting Safety, Lance Operation, Pump Overhaul',
            'education' => 'SMK Teknik Mesin',
            'daily_rate' => 550000,
            'emergency_contact_name' => 'Wahyuni (Istri)',
            'emergency_contact_phone' => '081399881122',
        ]);

        $empOps2 = $ensureEmployee('Agus Setiawan', 'agus.torque@elbema.co.id', 'EMP-OPS-012', $posBoltTech, $opsDept, [
            'employment_status' => 'contract',
            'work_location' => 'Site Palembang (Pusri)',
            'skills' => 'Hydraulic Torque Wrench, Bolt Tensioning Tentec, Flange Alignment, Hot Bolting',
            'education' => 'Politeknik Mesin',
            'daily_rate' => 500000,
            'emergency_contact_name' => 'Sri Astuti',
            'emergency_contact_phone' => '081399881133',
        ]);

        $empHse1 = $ensureEmployee('Rahmat Pratama, S.T.', 'rahmat.hse@elbema.co.id', 'EMP-HSE-005', $posHseInspector, $hsseDept, [
            'employment_status' => 'permanent',
            'work_location' => 'Site Sorowako (Vale)',
            'skills' => 'AK3 Umum Kemenaker, CSMS Auditor, HIRADC, Incident Investigation, PTW Assessor',
            'education' => 'S1 Teknik Lingkungan',
            'daily_rate' => 750000,
            'emergency_contact_name' => 'Bambang Pratama (Ayah)',
            'emergency_contact_phone' => '081399881144',
        ]);

        // Get active projects for relations
        $project1 = Project::where('project_code', 'PRJ-KPI-2026-001')->first() ?? Project::first();
        $project2 = Project::where('project_code', 'SO-26-001')->first() ?? Project::skip(1)->first();

        // 3. Training & Certification Master Catalog
        $trainings = [
            [
                'title' => 'K3 Confined Space Entry & Rescue (Kemenaker RI)',
                'category' => 'Safety & K3',
                'institution' => 'Kemenaker RI / PJK3 Mitra',
                'validity_years' => 3,
                'description' => 'Sertifikasi kompetensi petugas madya & utama ruang terbatas (Confined Space) standar keselamatan industri migas/pupuk.',
            ],
            [
                'title' => 'Working at Height (WAH) Level 2 BNSP',
                'category' => 'Safety & K3',
                'institution' => 'Badan Nasional Sertifikasi Profesi (BNSP)',
                'validity_years' => 3,
                'description' => 'Kompetensi bekerja pada ketinggian menggunakan Full Body Harness, Fall Arrester, dan Rescue Kit.',
            ],
            [
                'title' => 'Ultra High Pressure (UHP) Hydroblaster Operator & Safety',
                'category' => 'Technical Specialist',
                'institution' => 'WJTA (WaterJet Technology Association) / Petrotekno',
                'validity_years' => 2,
                'description' => 'Pengoperasian mesin hydrojet tekanan ekstrem 15.000 - 40.000 PSI dan pencegahan insiden semburan nozzle.',
            ],
            [
                'title' => 'Hydraulic Torque Wrench & Bolt Tensioning Specialist',
                'category' => 'Technical Specialist',
                'institution' => 'Enerpac Academy / TUV Rheinland',
                'validity_years' => 3,
                'description' => 'Kalkulasi beban torsi, elongasi baut flange pipa tekan tinggi, dan prosedur hot bolting.',
            ],
            [
                'title' => 'Ahli K3 Umum (AK3U) Sertifikasi Kemenaker',
                'category' => 'Safety & K3',
                'institution' => 'Direktorat PNK3 Kemnaker RI',
                'validity_years' => 3,
                'description' => 'Kualifikasi pengawas K3 perundangan untuk memimpin penerapan SMK3 di lingkungan kerja.',
            ],
            [
                'title' => 'Rigger & Lifting Operation Level 1 (Migas)',
                'category' => 'Technical Specialist',
                'institution' => 'PPSDM Migas Cepu',
                'validity_years' => 3,
                'description' => 'Perhitungan kapasitas sling kawat baja, shackle, dan koordinasi pengangkatan beban berat.',
            ],
        ];

        $trainingModels = [];
        foreach ($trainings as $tr) {
            $trainingModels[$tr['title']] = HcdTraining::firstOrCreate(
                ['title' => $tr['title']],
                $tr
            );
        }

        // 4. Employee Training Records (With Expired & Expiring Soon for Dashboard Highlights!)
        $allEmployees = Employee::all();
        if ($allEmployees->count() > 0) {
            $emp1 = $allEmployees->first();
            $emp2 = $allEmployees->skip(1)->first() ?? $emp1;
            $emp3 = $allEmployees->skip(2)->first() ?? $emp1;

            // Valid Certification
            HcdEmployeeTraining::updateOrCreate(
                ['certificate_number' => 'CERT-K3CS-2024-0891'],
                [
                    'employee_id' => $empOps1->id,
                    'training_id' => $trainingModels['K3 Confined Space Entry & Rescue (Kemenaker RI)']->id,
                    'issue_date' => Carbon::now()->subMonths(10)->toDateString(),
                    'expiry_date' => Carbon::now()->addMonths(26)->toDateString(),
                    'status' => HcdEmployeeTraining::STATUS_VALID,
                    'cost' => 4500000,
                    'notes' => 'Lulus dengan nilai evaluasi praktek 92/100.',
                ]
            );

            // UHP Operator - Expiring soon (<20 days!)
            HcdEmployeeTraining::updateOrCreate(
                ['certificate_number' => 'CERT-UHP-2023-0144'],
                [
                    'employee_id' => $empOps1->id,
                    'training_id' => $trainingModels['Ultra High Pressure (UHP) Hydroblaster Operator & Safety']->id,
                    'issue_date' => Carbon::now()->subYears(2)->toDateString(),
                    'expiry_date' => Carbon::now()->addDays(18)->toDateString(), // Mendekati expired!
                    'status' => HcdEmployeeTraining::STATUS_EXPIRING_SOON,
                    'cost' => 6000000,
                    'notes' => 'Perlu penjadwalan refresher training sebelum mobilisasi TA berikutnya.',
                ]
            );

            // Torque Wrench - Expired (Warning alert!)
            HcdEmployeeTraining::updateOrCreate(
                ['certificate_number' => 'CERT-TRQ-2022-0056'],
                [
                    'employee_id' => $empOps2->id,
                    'training_id' => $trainingModels['Hydraulic Torque Wrench & Bolt Tensioning Specialist']->id,
                    'issue_date' => Carbon::now()->subYears(3)->subMonths(2)->toDateString(),
                    'expiry_date' => Carbon::now()->subMonths(2)->toDateString(), // Sudah kadaluarsa!
                    'status' => HcdEmployeeTraining::STATUS_EXPIRED,
                    'cost' => 5200000,
                    'notes' => 'Sertifikat telah lewat masa berlaku, wajib re-sertifikasi BNSP.',
                ]
            );

            // AK3 Umum - Valid
            HcdEmployeeTraining::updateOrCreate(
                ['certificate_number' => 'KEMNAKER-AK3-2025-7781'],
                [
                    'employee_id' => $empHse1->id,
                    'training_id' => $trainingModels['Ahli K3 Umum (AK3U) Sertifikasi Kemenaker']->id,
                    'issue_date' => Carbon::now()->subMonths(6)->toDateString(),
                    'expiry_date' => Carbon::now()->addMonths(30)->toDateString(),
                    'status' => HcdEmployeeTraining::STATUS_VALID,
                    'cost' => 7500000,
                    'notes' => 'SKP Kemnaker aktif sampai 2028.',
                ]
            );

            // WAH Level 2 - In Training
            HcdEmployeeTraining::updateOrCreate(
                ['certificate_number' => 'REG-WAH-2026-PENDING'],
                [
                    'employee_id' => $empOps2->id,
                    'training_id' => $trainingModels['Working at Height (WAH) Level 2 BNSP']->id,
                    'issue_date' => Carbon::now()->subDays(5)->toDateString(),
                    'expiry_date' => Carbon::now()->addYears(3)->toDateString(),
                    'status' => HcdEmployeeTraining::STATUS_IN_TRAINING,
                    'cost' => 3800000,
                    'notes' => 'Sedang mengikuti ujian asesmen kompetensi di Petrotekno.',
                ]
            );
        }

        // 5. Task Monitoring Records
        $tasksData = [
            [
                'task_code' => 'TSK-HCD-2026-001',
                'title' => 'Verifikasi MCU & Induction Kit Turnaround Pusri',
                'description' => 'Pengecekan berkas Medical Check Up (Fit to Work) dan sertifikat K3 18 personel site untuk mobilisasi Turnaround Pabrik 1B.',
                'assigned_to' => $empHcd->id,
                'project_id' => $project2?->id,
                'department_id' => $hcdDept->id,
                'priority' => HcdTask::PRIORITY_HIGH,
                'status' => HcdTask::STATUS_IN_PROGRESS,
                'due_date' => Carbon::now()->addDays(3)->toDateString(),
                'estimated_hours' => 12.00,
                'actual_hours' => 6.50,
            ],
            [
                'task_code' => 'TSK-HCD-2026-002',
                'title' => 'Pendaftaran Ujian Re-sertifikasi UHP Waterjetting Operator',
                'description' => 'Mendaftarkan 4 teknisi hydroblasting untuk uji kompetensi BNSP di Cilegon Training Center.',
                'assigned_to' => $empHcd->id,
                'project_id' => null,
                'department_id' => $hcdDept->id,
                'priority' => HcdTask::PRIORITY_URGENT,
                'status' => HcdTask::STATUS_PENDING,
                'due_date' => Carbon::now()->addDays(5)->toDateString(),
                'estimated_hours' => 8.00,
                'actual_hours' => 0.00,
            ],
            [
                'task_code' => 'TSK-OPS-2026-003',
                'title' => 'Inspeksi & Kalibrasi Torque Wrench Enerpac W4000',
                'description' => 'Pemeriksaan gauge pressure dan penggantian seal hidrolik sebelum pengiriman ke Job Site Bontang.',
                'assigned_to' => $empOps2->id,
                'project_id' => $project1?->id,
                'department_id' => $opsDept->id,
                'priority' => HcdTask::PRIORITY_HIGH,
                'status' => HcdTask::STATUS_REVIEW,
                'due_date' => Carbon::now()->addDays(1)->toDateString(),
                'estimated_hours' => 6.00,
                'actual_hours' => 5.00,
            ],
            [
                'task_code' => 'TSK-HSE-2026-004',
                'title' => 'Penyusunan JSA & CSMS Dossier PT Vale Indonesia',
                'description' => 'Membuat dokumen Job Safety Analysis untuk pekerjaan pembersihan tangki asam sulfat konsentrasi 98%.',
                'assigned_to' => $empHse1->id,
                'project_id' => $project1?->id,
                'department_id' => $hsseDept->id,
                'priority' => HcdTask::PRIORITY_URGENT,
                'status' => HcdTask::STATUS_IN_PROGRESS,
                'due_date' => Carbon::now()->addDays(2)->toDateString(),
                'estimated_hours' => 16.00,
                'actual_hours' => 10.00,
            ],
            [
                'task_code' => 'TSK-OPS-2026-005',
                'title' => 'Rekapitulasi Timesheet & Overtime Tim Turnaround KPI',
                'description' => 'Audit absensi harian dan lembur 25 kru hydrojetting selama periode shutdown 14 hari.',
                'assigned_to' => $empOps1->id,
                'project_id' => $project1?->id,
                'department_id' => $opsDept->id,
                'priority' => HcdTask::PRIORITY_MEDIUM,
                'status' => HcdTask::STATUS_COMPLETED,
                'due_date' => Carbon::now()->subDays(2)->toDateString(),
                'completed_at' => Carbon::now()->subDays(1),
                'estimated_hours' => 8.00,
                'actual_hours' => 7.50,
            ],
        ];

        foreach ($tasksData as $tData) {
            HcdTask::updateOrCreate(
                ['task_code' => $tData['task_code']],
                $tData
            );
        }

        // 6. Professional Database (Talent Pool Tenaga Ahli & Subkontraktor Spesialis)
        $professionals = [
            [
                'full_name' => 'Ir. Muhammad Danang, IPM',
                'specialization' => 'Hot Tapping & Line Stopping Superintendent',
                'category' => 'Mechanical & Piping',
                'contact_email' => 'danang.hottap@gmail.com',
                'contact_phone' => '081122334455',
                'city' => 'Balikpapan',
                'experience_years' => 14,
                'daily_rate' => 2200000,
                'availability_status' => HcdProfessional::AVAILABILITY_AVAILABLE,
                'current_project_id' => null,
                'certifications_summary' => 'API 2201 Hot Tapping, CSMS Pertamina, AK3 Migas, ASME B31.3 Piping Specialist',
                'rating' => 4.95,
                'notes' => 'Berpengalaman menangani hot tapping live pipeline gas alam tekanan hingga 120 bar di Pertamina Hulu Mahakam.',
            ],
            [
                'full_name' => 'Bambang Supriyadi, S.T.',
                'specialization' => 'Senior NDT Level III & Ultrasonic Phased Array',
                'category' => 'Inspection & QA/QC',
                'contact_email' => 'bambang.ndt@yahoo.com',
                'contact_phone' => '081233445566',
                'city' => 'Surabaya',
                'experience_years' => 12,
                'daily_rate' => 1850000,
                'availability_status' => HcdProfessional::AVAILABILITY_MOBILIZED,
                'current_project_id' => $project1?->id,
                'certifications_summary' => 'ASNT Level III (UT, RT, MT, PT), PCN PAUT Level II, CSWIP 3.1 Welding Inspector',
                'rating' => 4.90,
                'notes' => 'Sedang bertugas mengawasi inspeksi ketebalan sisa pelat boiler dan spherical tank di Bontang.',
            ],
            [
                'full_name' => 'Hendra Gunawan',
                'specialization' => 'Hydroblasting & Chemical Cleaning Specialist',
                'category' => 'Surface Preparation',
                'contact_email' => 'hendra.gunawan@outlook.com',
                'contact_phone' => '081344556677',
                'city' => 'Palembang',
                'experience_years' => 9,
                'daily_rate' => 1350000,
                'availability_status' => HcdProfessional::AVAILABILITY_STANDBY,
                'current_project_id' => null,
                'certifications_summary' => 'WJTA Hydroblasting Safety, K3 Confined Space Utama, NACE CIP Level 1',
                'rating' => 4.80,
                'notes' => 'Pakar kimia citric acid wash dan passivation pada tube heat exchanger stainless steel 316L.',
            ],
            [
                'full_name' => 'Faisal Basri',
                'specialization' => 'Flange Management & Controlled Bolting Lead',
                'category' => 'Mechanical & Piping',
                'contact_email' => 'faisal.bolting@gmail.com',
                'contact_phone' => '081255667788',
                'city' => 'Cilegon',
                'experience_years' => 8,
                'daily_rate' => 1250000,
                'availability_status' => HcdProfessional::AVAILABILITY_AVAILABLE,
                'current_project_id' => null,
                'certifications_summary' => 'ECITB MJI10/19 (Hydraulically Torque & Tensioned Bolted Connection), WAH Level 2',
                'rating' => 4.75,
                'notes' => 'Memiliki portofolio zero-leak flanged joints pada turnaround kilang Chandra Asri dan Lotte Chemical.',
            ],
            [
                'full_name' => 'Agus Budiman, S.T.',
                'specialization' => 'Lead CSMS & Plant Turnaround Safety Assessor',
                'category' => 'HSSE Field',
                'contact_email' => 'agus.budiman.hse@gmail.com',
                'contact_phone' => '081166778899',
                'city' => 'Makassar',
                'experience_years' => 11,
                'daily_rate' => 1650000,
                'availability_status' => HcdProfessional::AVAILABILITY_AVAILABLE,
                'current_project_id' => null,
                'certifications_summary' => 'NEBOSH IGC, Ahli K3 Kimia Kemenaker, Auditor SMK3 Kemnaker, Incident Root Cause RCFA',
                'rating' => 4.88,
                'notes' => 'Rekomendasi kuat untuk memimpin HSE induction dan safety gate inspection pada proyek Vale Sorowako.',
            ],
        ];

        foreach ($professionals as $prof) {
            HcdProfessional::updateOrCreate(
                ['contact_phone' => $prof['contact_phone']],
                $prof
            );
        }
    }
}
