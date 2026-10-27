<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\FollowUp;
use App\Models\Opportunity;
use App\Models\Project;
use App\Models\Proposal;
use App\Models\SalesActivity;
use App\Models\User;
use Illuminate\Database\Seeder;

class SalesCrmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Customers
        $customersData = [
            [
                'company_name' => 'PT Kaltim Parna Industri (Bontang)',
                'location' => 'Kawasan Industri Kaltim Parna Industri, Bontang, Kalimantan Timur',
                'industry' => 'Petrochemical / Anhydrous Ammonia',
                'npwp' => '01.345.678.9-724.000',
                'pic_name' => 'Ir. Bambang S.',
                'pic_phone' => '08121111222',
                'pic_email' => 'bambang.s@kpi.co.id',
            ],
            [
                'company_name' => 'PT Pupuk Sriwidjaja (Palembang)',
                'location' => 'Jl. Mayor Zen, Palembang, Sumatera Selatan',
                'industry' => 'Fertilizer & Chemical Manufacturing',
                'npwp' => '01.234.567.8-301.000',
                'pic_name' => 'Hendra Wijaya',
                'pic_phone' => '08122222333',
                'pic_email' => 'hendra.w@pusri.co.id',
            ],
            [
                'company_name' => 'PT Vale Indonesia (Sorowako)',
                'location' => 'Sorowako, Luwu Timur, Sulawesi Selatan',
                'industry' => 'Nickel Mining & Smelting',
                'npwp' => '01.456.789.0-801.000',
                'pic_name' => 'Michael Tan',
                'pic_phone' => '08123333444',
                'pic_email' => 'michael.tan@vale.com',
            ],
            [
                'company_name' => 'PT Pupuk Kalimantan Timur (Bontang)',
                'location' => 'Jl. James Simandjuntak No. 1, Bontang, Kalimantan Timur',
                'industry' => 'Fertilizer & Petrochemical',
                'npwp' => '01.567.890.1-724.000',
                'pic_name' => 'Achmad Fauzi',
                'pic_phone' => '08124444555',
                'pic_email' => 'fauzi.a@pupukkaltim.com',
            ],
            [
                'company_name' => 'PT Samator',
                'location' => 'Rungkut Industri, Surabaya, Jawa Timur',
                'industry' => 'Industrial & Medical Gases',
                'npwp' => '01.678.901.2-601.000',
                'pic_name' => 'Stephanie Pratama',
                'pic_phone' => '08125555666',
                'pic_email' => 'stephanie@samator.com',
            ],
        ];

        $customers = [];
        foreach ($customersData as $c) {
            $customers[$c['company_name']] = Customer::updateOrCreate(
                ['company_name' => $c['company_name']],
                $c
            );
        }

        // Fetch sales executive users
        $andi = User::where('email', 'andi.r@elbema.co.id')->first();
        $dimas = User::where('email', 'dimas.p@elbema.co.id')->first();
        $nisa = User::where('email', 'nisa.a@elbema.co.id')->first();
        $pmEmployee = Employee::where('employee_number', 'EMP-PMO-001')->first();

        // 2. Scenario 1: Closed Won Opportunity + Project for PT Kaltim Parna Industri
        if ($andi) {
            $opp1 = Opportunity::updateOrCreate(
                ['title' => 'Spherical Tank Cleaning CO₂ Unit 12'],
                [
                    'customer_id' => $customers['PT Kaltim Parna Industri (Bontang)']->id,
                    'owner_id' => $andi->id,
                    'estimated_value' => 1850000000.00,
                    'stage' => Opportunity::STAGE_CLOSED_WON,
                    'next_step' => 'Kick-off meeting PMO & site mobilization',
                    'target_close_date' => '2026-03-31',
                ]
            );

            Proposal::updateOrCreate(
                ['proposal_number' => 'PROP-ELB-2026-0042'],
                [
                    'opportunity_id' => $opp1->id,
                    'amount' => 1850000000.00,
                    'status' => Proposal::STATUS_ACCEPTED,
                    'submitted_at' => '2026-02-15 10:30:00',
                ]
            );

            SalesActivity::firstOrCreate(
                ['title' => 'Technical Clarification Meeting di KPI Head Office Bontang', 'opportunity_id' => $opp1->id],
                [
                    'user_id' => $andi->id,
                    'type' => SalesActivity::TYPE_OUTSIDE_MEETING,
                    'notes' => 'Membahas prosedur safety confined space entry & sertifikasi peralatan scaffolding.',
                    'activity_at' => '2026-02-10 14:00:00',
                ]
            );

            SalesActivity::firstOrCreate(
                ['title' => 'Penyerahan Dokumen Penawaran Komersial & Teknis', 'opportunity_id' => $opp1->id],
                [
                    'user_id' => $andi->id,
                    'type' => SalesActivity::TYPE_PROPOSAL_SUBMITTED,
                    'notes' => 'Dokumen hardcopy & softcopy diserahkan ke panitia pengadaan Turnaround KPI.',
                    'activity_at' => '2026-02-15 11:00:00',
                ]
            );

            SalesActivity::firstOrCreate(
                ['title' => 'Final Negotiation & Letter of Intent (LOI) Signing', 'opportunity_id' => $opp1->id],
                [
                    'user_id' => $andi->id,
                    'type' => SalesActivity::TYPE_CUSTOMER_VISIT,
                    'notes' => 'Disepakati nilai kontrak Rp 1.850.000.000 include PPN dengan term of payment 30-50-20.',
                    'activity_at' => '2026-03-25 09:30:00',
                ]
            );

            FollowUp::updateOrCreate(
                ['task_title' => 'Kirim draft kontrak & koordinasi dokumen CSMS dengan HSSE KPI', 'opportunity_id' => $opp1->id],
                [
                    'assigned_to' => $andi->id,
                    'due_date' => '2026-04-05',
                    'is_completed' => true,
                ]
            );

            // Create PMO Project active execution
            if ($pmEmployee) {
                Project::updateOrCreate(
                    ['project_code' => 'PRJ-KPI-2026-001'],
                    [
                        'opportunity_id' => $opp1->id,
                        'customer_id' => $customers['PT Kaltim Parna Industri (Bontang)']->id,
                        'project_manager_id' => $pmEmployee->id,
                        'title' => 'Spherical Tank Cleaning CO₂ Unit 12 Turnaround',
                        'contract_value' => 1850000000.00,
                        'start_date' => '2026-04-10',
                        'end_date' => '2026-06-30',
                        'status' => Project::STATUS_ON_PROGRESS,
                        'progress_percentage' => 35,
                    ]
                );
            }
        }

        // 3. Scenario 2: Negotiation Stage for PT Pupuk Kalimantan Timur (Bontang)
        if ($dimas) {
            $opp2 = Opportunity::updateOrCreate(
                ['title' => 'Online Leak Sealing Service (OLSS) Ammonia Converter'],
                [
                    'customer_id' => $customers['PT Pupuk Kalimantan Timur (Bontang)']->id,
                    'owner_id' => $dimas->id,
                    'estimated_value' => 3400000000.00,
                    'stage' => Opportunity::STAGE_NEGOTIATION,
                    'next_step' => 'Klarifikasi final commercial scope dengan Tim Pengadaan',
                    'target_close_date' => '2026-11-15',
                ]
            );

            Proposal::updateOrCreate(
                ['proposal_number' => 'PROP-ELB-2026-0089'],
                [
                    'opportunity_id' => $opp2->id,
                    'amount' => 3400000000.00,
                    'status' => Proposal::STATUS_SUBMITTED,
                    'submitted_at' => '2026-09-18 09:00:00',
                ]
            );

            SalesActivity::firstOrCreate(
                ['title' => 'Presentasi Metodologi OLSS Clamping High Temperature', 'opportunity_id' => $opp2->id],
                [
                    'user_id' => $dimas->id,
                    'type' => SalesActivity::TYPE_ONLINE_PRESENTATION,
                    'notes' => 'Pemaparan kemampuan compound sealant tahan temperatur 450°C pada converter ammonia.',
                    'activity_at' => '2026-09-10 10:00:00',
                ]
            );

            SalesActivity::firstOrCreate(
                ['title' => 'Site Walkdown & Dimensional Check Flange Converter', 'opportunity_id' => $opp2->id],
                [
                    'user_id' => $dimas->id,
                    'type' => SalesActivity::TYPE_CUSTOMER_VISIT,
                    'notes' => 'Pengukuran dimensi nozzle dan clearance area instalasi hydraulic clamping box.',
                    'activity_at' => '2026-09-22 13:30:00',
                ]
            );

            FollowUp::updateOrCreate(
                ['task_title' => 'Submit penyesuaian breakdown delivery time clamp material', 'opportunity_id' => $opp2->id],
                [
                    'assigned_to' => $dimas->id,
                    'due_date' => '2026-10-25',
                    'is_completed' => false,
                ]
            );
        }

        // 4. Scenario 3: Proposal Submitted Stage for PT Pupuk Sriwidjaja (Palembang)
        if ($nisa) {
            $opp3 = Opportunity::updateOrCreate(
                ['title' => 'Heat Exchanger Bundle Hydrojetting 20,000 PSI'],
                [
                    'customer_id' => $customers['PT Pupuk Sriwidjaja (Palembang)']->id,
                    'owner_id' => $nisa->id,
                    'estimated_value' => 950000000.00,
                    'stage' => Opportunity::STAGE_PROPOSAL_SUBMITTED,
                    'next_step' => 'Menunggu evaluasi teknis & jadwal klarifikasi user Pusri',
                    'target_close_date' => '2026-11-30',
                ]
            );

            Proposal::updateOrCreate(
                ['proposal_number' => 'PROP-ELB-2026-0105'],
                [
                    'opportunity_id' => $opp3->id,
                    'amount' => 950000000.00,
                    'status' => Proposal::STATUS_SUBMITTED,
                    'submitted_at' => '2026-10-05 14:00:00',
                ]
            );

            SalesActivity::firstOrCreate(
                ['title' => 'Diskusi scope cleaning HE Pusri-IIB dengan Plant Maintenance Dept', 'opportunity_id' => $opp3->id],
                [
                    'user_id' => $nisa->id,
                    'type' => SalesActivity::TYPE_OUTSIDE_MEETING,
                    'notes' => 'Review scale hardness dan tube sheet thickness sebelum pembersihan.',
                    'activity_at' => '2026-09-28 11:00:00',
                ]
            );

            FollowUp::updateOrCreate(
                ['task_title' => 'Follow-up evaluasi teknis ke Tim Engineering Pusri', 'opportunity_id' => $opp3->id],
                [
                    'assigned_to' => $nisa->id,
                    'due_date' => '2026-10-28',
                    'is_completed' => false,
                ]
            );
        }

        // 5. Scenario 4: Tender RFQ Stage for PT Vale Indonesia (Sorowako)
        if ($andi) {
            $opp4 = Opportunity::updateOrCreate(
                ['title' => 'Supply of Industrial High-Pressure Ball Valves & Slurry Pumps'],
                [
                    'customer_id' => $customers['PT Vale Indonesia (Sorowako)']->id,
                    'owner_id' => $andi->id,
                    'estimated_value' => 2750000000.00,
                    'stage' => Opportunity::STAGE_TENDER_RFQ,
                    'next_step' => 'Review dokumen tender RFQ & verifikasi technical spec sheet',
                    'target_close_date' => '2026-12-20',
                ]
            );

            Proposal::updateOrCreate(
                ['proposal_number' => 'PROP-ELB-2026-0118'],
                [
                    'opportunity_id' => $opp4->id,
                    'amount' => 2750000000.00,
                    'status' => Proposal::STATUS_DRAFT,
                    'submitted_at' => null,
                ]
            );

            SalesActivity::firstOrCreate(
                ['title' => 'Pre-bid Meeting RFQ No. VI-PROC-2026-781', 'opportunity_id' => $opp4->id],
                [
                    'user_id' => $andi->id,
                    'type' => SalesActivity::TYPE_ONLINE_PRESENTATION,
                    'notes' => 'Mengklarifikasi batas toleransi materi slurry valve ANSI 600# Titanium alloy.',
                    'activity_at' => '2026-10-12 09:00:00',
                ]
            );

            FollowUp::updateOrCreate(
                ['task_title' => 'Dapatkan dukungan prinsipal untuk certificate of origin valve', 'opportunity_id' => $opp4->id],
                [
                    'assigned_to' => $andi->id,
                    'due_date' => '2026-11-02',
                    'is_completed' => false,
                ]
            );
        }

        // 6. Scenario 5: Need Analysis Stage for PT Samator
        if ($dimas) {
            $opp5 = Opportunity::updateOrCreate(
                ['title' => 'Robotic Internal Tank Inspection & De-sludging LOX/LIN Tank'],
                [
                    'customer_id' => $customers['PT Samator']->id,
                    'owner_id' => $dimas->id,
                    'estimated_value' => 650000000.00,
                    'stage' => Opportunity::STAGE_NEED_ANALYSIS,
                    'next_step' => 'Penyusunan preliminary budgetary quotation & safety protocol',
                    'target_close_date' => '2026-12-15',
                ]
            );

            SalesActivity::firstOrCreate(
                ['title' => 'Survey akses manhole & kelistrikan ATEX zone di plant Samator', 'opportunity_id' => $opp5->id],
                [
                    'user_id' => $dimas->id,
                    'type' => SalesActivity::TYPE_CUSTOMER_VISIT,
                    'notes' => 'Pemeriksaan kepatuhan explosion-proof crawler robot terhadap regulasi keselamatan O2 murni.',
                    'activity_at' => '2026-10-14 14:30:00',
                ]
            );

            FollowUp::updateOrCreate(
                ['task_title' => 'Kirim video demo robot tank cleaning ke Technical Manager', 'opportunity_id' => $opp5->id],
                [
                    'assigned_to' => $dimas->id,
                    'due_date' => '2026-10-24',
                    'is_completed' => false,
                ]
            );
        }
    }
}
