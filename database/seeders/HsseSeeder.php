<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\HsseIncident;
use App\Models\HsseInspection;
use App\Models\HssePermit;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class HsseSeeder extends Seeder
{
    public function run(): void
    {
        $project1 = Project::where('project_code', 'PRJ-KPI-2026-001')->first() ?? Project::first();
        $project2 = Project::where('project_code', 'SO-26-001')->first() ?? Project::skip(1)->first();

        $hseOfficer = Employee::whereHas('position', function ($q) {
            $q->where('title', 'like', '%HSSE%');
        })->first() ?? Employee::first();

        $technician = Employee::whereHas('position', function ($q) {
            $q->where('title', 'like', '%Tech%');
        })->first() ?? Employee::skip(1)->first() ?? $hseOfficer;

        // 1. Safety Permits & JSA (Permit to Work / PTW)
        $permitsData = [
            [
                'permit_number' => 'PTW-2026-0101',
                'title' => 'UHP Waterjetting & Sludge Removal Inside CO2 Spherical Tank',
                'permit_type' => 'Confined Space Entry',
                'project_id' => $project1?->id,
                'location' => 'Area Tank Farm Spherical Unit 12 (Bontang)',
                'applicant_id' => $technician?->id,
                'safety_officer_id' => $hseOfficer?->id,
                'start_time' => Carbon::today()->setTime(7, 30),
                'end_time' => Carbon::today()->setTime(17, 30),
                'risk_level' => 'High',
                'jsa_content' => "1. Bahaya Asfiksia CO2 sisa: Uji gas atmosfer continuous & exhaust blower aktif.\n2. Bahaya semburan air 40.000 PSI: Kru wajib menggunakan APD Kevlar & foot protector.\n3. Akses ruang terbatas: Standby man di luar manhole dengan log book & rescue tripod.",
                'gas_test_required' => true,
                'gas_test_o2' => '20.9%',
                'gas_test_lel' => '0%',
                'gas_test_co' => '0 ppm',
                'gas_test_h2s' => '0 ppm',
                'ppe_requirements' => 'Full Body Harness, Kevlar Waterjet Gaiters, Ear Muff, Safety Helmet with Chin Strap, Nitrile Gloves, Gas Detector Personal',
                'status' => HssePermit::STATUS_ACTIVE,
                'notes' => 'Toolbox meeting telah dilakukan pukul 07:15 dengan 8 kru site.',
            ],
            [
                'permit_number' => 'PTW-2026-0102',
                'title' => 'Hot Bolting & Hydraulic Torque Flange Nozzle Converter',
                'permit_type' => 'Hot Work',
                'project_id' => $project2?->id,
                'location' => 'Plant 1B Converter Primary Section (Palembang)',
                'applicant_id' => $technician?->id,
                'safety_officer_id' => $hseOfficer?->id,
                'start_time' => Carbon::today()->setTime(8, 0),
                'end_time' => Carbon::today()->setTime(16, 0),
                'risk_level' => 'High',
                'jsa_content' => "1. Bahaya kebocoran gas ammonia panas: Continuous sniffing detector H2S/NH3.\n2. Bahaya tekanan hidrolik: Cek selang Enerpac 700 bar & pasang whipcheck safety.\n3. Suhu permukaan pipa 350°C: Gunakan sarung tangan tahan panas aramid.",
                'gas_test_required' => true,
                'gas_test_o2' => '20.8%',
                'gas_test_lel' => '0%',
                'gas_test_co' => '2 ppm',
                'gas_test_h2s' => '0 ppm',
                'ppe_requirements' => 'Fire Retardant Nomex Wearpack, Heat Resistant Gloves, Full Face Shield, Safety Boots, APAR Powder 6kg on stand',
                'status' => HssePermit::STATUS_APPROVED,
                'notes' => 'Menunggu verifikasi operator plant sebelum eksekusi torsi baut flange.',
            ],
            [
                'permit_number' => 'PTW-2026-0103',
                'title' => 'Pemasangan Scaffolding Tubular & Penggantian Valve Elevasi +24m',
                'permit_type' => 'Working at Height',
                'project_id' => $project1?->id,
                'location' => 'Structure Column C-201 Unit Karbamasi',
                'applicant_id' => $technician?->id,
                'safety_officer_id' => $hseOfficer?->id,
                'start_time' => Carbon::yesterday()->setTime(8, 0),
                'end_time' => Carbon::yesterday()->setTime(17, 0),
                'risk_level' => 'High',
                'jsa_content' => "1. Bahaya jatuh dari ketinggian: 100% tie-off dengan double lanyard absorber.\n2. Bahaya benda jatuh (dropped objects): Pasang toe board, safety net, dan barricade area bawah radius 5 meter.",
                'gas_test_required' => false,
                'ppe_requirements' => 'Full Body Harness Double Lanyard, Chin Strap Helmet, Tool Lanyard, Safety Shoes',
                'status' => HssePermit::STATUS_CLOSED,
                'notes' => 'Pekerjaan selesai 100% tanpa insiden. Green tag scaffolding telah dilepas kembali.',
            ],
            [
                'permit_number' => 'PTW-2026-0104',
                'title' => 'Lockout / Tagout (LOTO) Motor Pompa Sirkulasi Feed Water',
                'permit_type' => 'Electrical / LOTO',
                'project_id' => $project2?->id,
                'location' => 'Substation MCC-02 Feed Water Pump Room',
                'applicant_id' => $technician?->id,
                'safety_officer_id' => $hseOfficer?->id,
                'start_time' => Carbon::today()->addDays(1)->setTime(9, 0),
                'end_time' => Carbon::today()->addDays(1)->setTime(15, 0),
                'risk_level' => 'Medium',
                'jsa_content' => "1. Bahaya sengatan listrik 6.6 kV: Isolasi circuit breaker utama & pasang master padlock LOTO.\n2. Verifikasi zero energy: Cek voltmeter tester sebelum membuka terminal box motor.",
                'gas_test_required' => false,
                'ppe_requirements' => 'Insulating Gloves Class 2, Arc Flash Visor, Safety Shoes Dielectric',
                'status' => HssePermit::STATUS_UNDER_REVIEW,
                'notes' => 'Menunggu persetujuan Electrical Superintendent.',
            ],
        ];

        foreach ($permitsData as $pData) {
            HssePermit::updateOrCreate(
                ['permit_number' => $pData['permit_number']],
                $pData
            );
        }

        // 2. Inspeksi Alat & APD (Equipment & PPE Inspections)
        $inspectionsData = [
            [
                'inspection_number' => 'INS-2026-0081',
                'item_name' => 'Ultra High Pressure (UHP) Pump Kamat K40000 300 kW',
                'item_category' => 'Heavy Machinery / Pump',
                'serial_number' => 'KMT-40K-2022-901',
                'project_id' => $project1?->id,
                'location' => 'Site Bontang Warehouse Area',
                'inspector_id' => $hseOfficer?->id,
                'inspection_date' => Carbon::now()->subDays(2)->toDateString(),
                'next_inspection_date' => Carbon::now()->addDays(28)->toDateString(),
                'result' => HsseInspection::RESULT_PASSED,
                'checklist_findings' => 'Emergency stop push button berfungsi instan. Pressure relief valve popping pada 41.500 PSI (OK). Suhu oli transmisi 62°C (Normal).',
                'corrective_action' => 'Tambahkan 1 liter oli pelumas crankcase sebelum mobilisasi ke area tangki.',
                'tag_color' => 'green',
            ],
            [
                'inspection_number' => 'INS-2026-0082',
                'item_name' => 'Personal 4-Gas Detector Crowcon Gasman (O2, LEL, CO, H2S)',
                'item_category' => 'Gas Detection & Monitoring',
                'serial_number' => 'CROW-GM-7721-JKT',
                'project_id' => $project1?->id,
                'location' => 'Safety Station Bontang Unit 12',
                'inspector_id' => $hseOfficer?->id,
                'inspection_date' => Carbon::now()->subDays(1)->toDateString(),
                'next_inspection_date' => Carbon::now()->addDays(6)->toDateString(),
                'result' => HsseInspection::RESULT_PASSED,
                'checklist_findings' => 'Bump test harian berhasil dengan span gas kalibrasi. Respon sensor O2 20.9%, H2S 25 ppm alarm berbunyi kencang.',
                'corrective_action' => null,
                'tag_color' => 'green',
            ],
            [
                'inspection_number' => 'INS-2026-0083',
                'item_name' => 'Hydraulic Torque Wrench Low Profile Enerpac W4000',
                'item_category' => 'Heavy Machinery / Pump',
                'serial_number' => 'ENR-W4K-2023-014',
                'project_id' => $project2?->id,
                'location' => 'Palembang Site Workshop',
                'inspector_id' => $hseOfficer?->id,
                'inspection_date' => Carbon::now()->subDays(4)->toDateString(),
                'next_inspection_date' => Carbon::now()->addDays(26)->toDateString(),
                'result' => HsseInspection::RESULT_PASSED_WITH_NOTES,
                'checklist_findings' => 'Drive unit ratchet presisi. Terdapat sedikit rembesan micro pada quick-coupler male hose B.',
                'corrective_action' => 'Ganti O-ring quick disconnect fitting sebelum pekerjaan torsi malam ini.',
                'tag_color' => 'yellow',
            ],
            [
                'inspection_number' => 'INS-2026-0084',
                'item_name' => 'Full Body Harness & Double Lanyard Karam PN-24',
                'item_category' => 'PPE & Fall Protection',
                'serial_number' => 'HAR-KRM-2024-089',
                'project_id' => $project1?->id,
                'location' => 'Scaffolding Storage Unit',
                'inspector_id' => $hseOfficer?->id,
                'inspection_date' => Carbon::now()->subDays(3)->toDateString(),
                'next_inspection_date' => Carbon::now()->addDays(180)->toDateString(),
                'result' => HsseInspection::RESULT_FAILED_QUARANTINED,
                'checklist_findings' => 'Webbing strap mengalami robek serat 3mm akibat gesekan tajam grating. Shock absorber terindikasi pernah terbentur.',
                'corrective_action' => 'UNIT DIKARANTINA & DIBERIKAN RED TAG. Dilarang digunakan kembali di lapangan, lakukan pemusnahan resmi.',
                'tag_color' => 'red',
            ],
            [
                'inspection_number' => 'INS-2026-0085',
                'item_name' => 'APAR Dry Chemical Powder 6 Kg Yamato ABC',
                'item_category' => 'Fire Protection / APAR',
                'serial_number' => 'YMT-DCP-6K-401',
                'project_id' => $project2?->id,
                'location' => 'Converter Laydown Area',
                'inspector_id' => $hseOfficer?->id,
                'inspection_date' => Carbon::now()->subDays(7)->toDateString(),
                'next_inspection_date' => Carbon::now()->addDays(23)->toDateString(),
                'result' => HsseInspection::RESULT_PASSED,
                'checklist_findings' => 'Jarum pressure gauge di zona hijau (15 bar). Segel pin utuh. Selang nozzle lentur tanpa retak.',
                'corrective_action' => 'Balik tabung 2-3 kali untuk mencegah penggumpalan serbuk powder.',
                'tag_color' => 'green',
            ],
        ];

        foreach ($inspectionsData as $iData) {
            HsseInspection::updateOrCreate(
                ['inspection_number' => $iData['inspection_number']],
                $iData
            );
        }

        // 3. Laporan Insiden & Investigasi (Incident Log & Near Miss)
        $incidentsData = [
            [
                'incident_number' => 'INC-2026-0001',
                'title' => 'Near Miss: Selang Hydroblasting 40k Bergetar Ekstrem Akibat Air Terjebak',
                'incident_type' => 'Near Miss',
                'project_id' => $project1?->id,
                'incident_date' => Carbon::now()->subDays(5)->setTime(14, 20),
                'location' => 'Area Depan Manhole Tangki CO2 Bontang',
                'reporter_id' => $technician?->id,
                'injured_person_id' => null,
                'description' => 'Saat memompa air awal, selang spiral tekanan tinggi mengalami lonjakan pukulan air (water hammer) dan menggesek sudut scaffold. Tidak ada personel terluka.',
                'root_cause' => 'Bleeding valve udara pada manifold pompa belum dibuka sempurna sebelum menaikkan rpm mesin diesel.',
                'immediate_action' => 'Mesin segera di-idle-kan dan selang diperiksa menyeluruh dari lecet. Whipcheck penahan terpasang aman.',
                'capa_action' => 'Revisi checklist SOP start-up pompa hydrojet untuk mewajibkan verifikasi bleeding udara 2 menit.',
                'target_date' => Carbon::now()->addDays(7)->toDateString(),
                'status' => HsseIncident::STATUS_CLOSED,
                'lost_workdays' => 0,
            ],
            [
                'incident_number' => 'INC-2026-0002',
                'title' => 'First Aid Case: Tergores Kawat Grating saat Memasang Selang Torsi',
                'incident_type' => 'First Aid Case (FAC)',
                'project_id' => $project2?->id,
                'incident_date' => Carbon::now()->subDays(2)->setTime(10, 45),
                'location' => 'Platform Elevasi +12m Area Converter Palembang',
                'reporter_id' => $hseOfficer?->id,
                'injured_person_id' => $technician?->id,
                'description' => 'Teknisi mengalami luka gores superfisial pada telapak tangan kanan sepanjang 1.5 cm saat menarik selang hidrolik di bawah grating baja.',
                'root_cause' => 'Teknisi menggunakan sarung tangan kain katun tipis, bukan sarung tangan impact/cut-resistant Level 5.',
                'immediate_action' => 'Pembersihan luka antiseptik di pos P3K site dan penutupan dengan plester steril. Personel kembali bekerja normal.',
                'capa_action' => 'Sosialisasi ulang kewajiban sarung tangan Cut-Resistant Kevlar Level 5 untuk seluruh kru mekanik di site.',
                'target_date' => Carbon::now()->addDays(3)->toDateString(),
                'status' => HsseIncident::STATUS_ACTION_PENDING,
                'lost_workdays' => 0,
            ],
        ];

        foreach ($incidentsData as $incData) {
            HsseIncident::updateOrCreate(
                ['incident_number' => $incData['incident_number']],
                $incData
            );
        }
    }
}
