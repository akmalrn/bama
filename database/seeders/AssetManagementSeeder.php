<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetAllocation;
use App\Models\AssetMaintenance;
use App\Models\Employee;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AssetManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pic = Employee::first();
        $pmoProject = Project::first();
        $today = Carbon::today();

        // 1. Master Assets
        $assetsData = [
            [
                'asset_code' => 'AST-2026-001',
                'name' => 'UHP Water Jetting Unit 40.000 PSI',
                'category' => 'High Pressure Pump',
                'brand_model' => 'Hammelmann HDP 170 / Diesel Driven',
                'serial_number' => 'HDP-40K-9921',
                'year_acquired' => 2022,
                'purchase_price' => 1850000000.00,
                'condition' => 'good',
                'status' => 'mobilized',
                'current_location' => 'Site Bontang (PKT Ammonia Plant)',
                'assigned_project_id' => $pmoProject?->id,
                'pic_employee_id' => $pic?->id,
                'last_maintenance_date' => $today->copy()->subDays(45),
                'next_maintenance_date' => $today->copy()->addDays(75),
                'last_calibration_date' => $today->copy()->subDays(60),
                'next_calibration_date' => $today->copy()->addDays(120),
                'technical_specifications' => 'Max Pressure: 40.000 PSI (2.800 bar), Flow rate: 26 L/min, Engine: Volvo Penta 6-Cyl Diesel.',
                'notes' => 'Unit prima, sedang ditugaskan di Turnaround Pupuk Kaltim Bontang.',
            ],
            [
                'asset_code' => 'AST-2026-002',
                'name' => 'Hydraulic Torque Wrench Low Profile 10.000 Nm',
                'category' => 'Flange & Bolting Tool',
                'brand_model' => 'Enerpac W-Series W8000',
                'serial_number' => 'ENR-TORQ-8812',
                'year_acquired' => 2023,
                'purchase_price' => 320000000.00,
                'condition' => 'good',
                'status' => 'available',
                'current_location' => 'Workshop Balikpapan',
                'assigned_project_id' => null,
                'pic_employee_id' => $pic?->id,
                'last_maintenance_date' => $today->copy()->subDays(20),
                'next_maintenance_date' => $today->copy()->addDays(100),
                'last_calibration_date' => $today->copy()->subDays(30),
                'next_calibration_date' => $today->copy()->addDays(150),
                'technical_specifications' => 'Torque Range: 1.150 - 11.200 Nm, Hexagon cassettes size: 50mm - 100mm, Operating pressure: 700 bar.',
                'notes' => 'Standby di Workshop Balikpapan, siap dimobilisasi kapan saja.',
            ],
            [
                'asset_code' => 'AST-2026-003',
                'name' => 'Industrial Pipe Cold Cutting & Beveling Machine',
                'category' => 'Machining Tool',
                'brand_model' => 'Wachs Split Frame SDSF 4"-12"',
                'serial_number' => 'WCH-CUT-4410',
                'year_acquired' => 2021,
                'purchase_price' => 450000000.00,
                'condition' => 'fair',
                'status' => 'mobilized',
                'current_location' => 'Site Sorowako (PT Vale Indonesia)',
                'assigned_project_id' => $pmoProject?->id,
                'pic_employee_id' => $pic?->id,
                'last_maintenance_date' => $today->copy()->subDays(60),
                'next_maintenance_date' => $today->copy()->addDays(30),
                'last_calibration_date' => null,
                'next_calibration_date' => null,
                'technical_specifications' => 'Pneumatic drive, OD range: 4 inch to 12 inch (100 - 300 mm), Cold cutting spark-free.',
                'notes' => 'Sedang dipakai pemotongan spool pipa slurry nickel.',
            ],
            [
                'asset_code' => 'AST-2026-004',
                'name' => 'Diesel Air Compressor High Pressure 900 CFM',
                'category' => 'Compressor & Power',
                'brand_model' => 'Atlas Copco XRVS 476',
                'serial_number' => 'ATC-900-3320',
                'year_acquired' => 2020,
                'purchase_price' => 1100000000.00,
                'condition' => 'maintenance_needed',
                'status' => 'under_maintenance',
                'current_location' => 'Yard Bontang Workshop',
                'assigned_project_id' => null,
                'pic_employee_id' => $pic?->id,
                'last_maintenance_date' => $today->copy()->subDays(120),
                'next_maintenance_date' => $today->copy()->subDays(5), // overdue
                'last_calibration_date' => $today->copy()->subDays(180),
                'next_calibration_date' => $today->copy()->addDays(10),
                'technical_specifications' => 'Delivery: 900 CFM, Working Pressure: 25 bar (365 psi), Engine: Caterpillar C13.',
                'notes' => 'Sedang overhaul valve pneumatic & ganti filter oli kompresor.',
            ],
            [
                'asset_code' => 'AST-2026-005',
                'name' => 'Hydrostatic Pressure Test Skid 15.000 PSI with Chart Recorder',
                'category' => 'Testing Instrument',
                'brand_model' => 'Maximator Portable Skid Unit',
                'serial_number' => 'MXM-HYD-5501',
                'year_acquired' => 2023,
                'purchase_price' => 280000000.00,
                'condition' => 'good',
                'status' => 'available',
                'current_location' => 'Workshop Balikpapan',
                'assigned_project_id' => null,
                'pic_employee_id' => $pic?->id,
                'last_maintenance_date' => $today->copy()->subDays(15),
                'next_maintenance_date' => $today->copy()->addDays(165),
                'last_calibration_date' => $today->copy()->subDays(25),
                'next_calibration_date' => $today->copy()->addDays(155),
                'technical_specifications' => 'Dual pump air-driven, Circular chart recorder Barton 2pen, Relief valve certified.',
                'notes' => 'Sertifikat kalibrasi aktif diterbitkan oleh Laboratorium Terakreditasi KAN.',
            ],
        ];

        foreach ($assetsData as $data) {
            Asset::updateOrCreate(['asset_code' => $data['asset_code']], $data);
        }

        $asset1 = Asset::where('asset_code', 'AST-2026-001')->first();
        $asset3 = Asset::where('asset_code', 'AST-2026-003')->first();
        $asset4 = Asset::where('asset_code', 'AST-2026-004')->first();

        // 2. Asset Allocations (Mobilisasi Proyek)
        if ($asset1 && $pmoProject) {
            AssetAllocation::updateOrCreate(
                ['allocation_number' => 'ALC-2026-001'],
                [
                    'asset_id' => $asset1->id,
                    'project_id' => $pmoProject->id,
                    'requested_by' => $pic?->id,
                    'approved_by' => $pic?->id,
                    'start_date' => $today->copy()->subDays(25),
                    'expected_return_date' => $today->copy()->addDays(35),
                    'status' => 'active',
                    'destination_site' => 'Pupuk Kaltim Bontang (Area Ammonia 1B)',
                    'courier_vehicle' => 'Trailer Flatbed B-9122-UW (PT Kaltim Logistik)',
                    'letter_of_handover_number' => 'BAST-AST/PKT/2026/088',
                    'condition_out' => 'Peralatan lengkap dengan high pressure hose 60m, lance, nozzle 40k psi terkalibrasi.',
                    'notes' => 'Mobilisasi resmi turnaround plant PT Pupuk Kaltim.',
                ]
            );
        }

        if ($asset3 && $pmoProject) {
            AssetAllocation::updateOrCreate(
                ['allocation_number' => 'ALC-2026-002'],
                [
                    'asset_id' => $asset3->id,
                    'project_id' => $pmoProject->id,
                    'requested_by' => $pic?->id,
                    'approved_by' => $pic?->id,
                    'start_date' => $today->copy()->subDays(15),
                    'expected_return_date' => $today->copy()->addDays(20),
                    'status' => 'active',
                    'destination_site' => 'Site Sorowako (Smelter Kiln Area)',
                    'courier_vehicle' => 'Ekspedisi Laut via Pelabuhan Kariangau',
                    'letter_of_handover_number' => 'BAST-AST/VALE/2026/041',
                    'condition_out' => 'Unit normal, mata pisau potong 1 set cadangan disertakan.',
                    'notes' => 'Pekerjaan perbaikan jalur pipa bertekanan.',
                ]
            );
        }

        // 3. Asset Maintenance & Calibration Records
        if ($asset4) {
            AssetMaintenance::updateOrCreate(
                ['maintenance_number' => 'MTC-2026-001'],
                [
                    'asset_id' => $asset4->id,
                    'type' => 'corrective',
                    'title' => 'Penggantian Valve Discharge & Servis Kompresor Udara 900 CFM',
                    'scheduled_date' => $today->copy()->subDays(5),
                    'completed_date' => null,
                    'cost' => 18500000.00,
                    'status' => 'in_progress',
                    'vendor_name' => 'PT Atlas Copco Indonesia Service Center',
                    'technician_employee_id' => $pic?->id,
                    'action_taken' => 'Pembongkaran unit air end, pembersihan filter separator, setting governor tekanan.',
                    'parts_replaced' => 'Kit Valve Discharge, Air Filter Heavy Duty, Seal O-Ring Heat Resistant.',
                    'certificate_number' => null,
                    'next_due_date' => $today->copy()->addDays(180),
                    'notes' => 'Sedang menunggu pengiriman suku cadang seal dari Jakarta.',
                ]
            );
        }

        if ($asset1) {
            AssetMaintenance::updateOrCreate(
                ['maintenance_number' => 'MTC-2026-002'],
                [
                    'asset_id' => $asset1->id,
                    'type' => 'calibration',
                    'title' => 'Kalibrasi Berkala Pressure Gauge & Rupture Disc 40.000 PSI',
                    'scheduled_date' => $today->copy()->subDays(60),
                    'completed_date' => $today->copy()->subDays(58),
                    'cost' => 4500000.00,
                    'status' => 'completed',
                    'vendor_name' => 'Balai Standardisasi & Kalibrasi Metrologi',
                    'technician_employee_id' => $pic?->id,
                    'action_taken' => 'Uji akurasi jarum penunjuk, sertifikasi batas toleransi tekanan kerja 0.5% F.S.',
                    'parts_replaced' => 'Rupture disc burst disc 42.000 psi replacement.',
                    'certificate_number' => 'KAN-CAL/2026/09912',
                    'next_due_date' => $today->copy()->addDays(120),
                    'notes' => 'Lolos sertifikasi dan aman untuk operasi lapangan.',
                ]
            );
        }
    }
}
