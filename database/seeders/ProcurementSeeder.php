<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\LogisticsDelivery;
use App\Models\ProcurementVendor;
use App\Models\Project;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProcurementSeeder extends Seeder
{
    public function run(): void
    {
        $project1 = Project::where('project_code', 'PRJ-KPI-2026-001')->first() ?? Project::first();
        $project2 = Project::where('project_code', 'SO-26-001')->first() ?? Project::skip(1)->first() ?? $project1;

        $superAdminUser = User::first();
        $requesterEmployee = Employee::first();
        $approverEmployee = Employee::skip(1)->first() ?? $requesterEmployee;

        // 1. Master Vendor / Rekanan Pengadaan Migas
        $vendors = [
            [
                'vendor_code' => 'VND-001',
                'name' => 'PT Valve & Piping Nusantara',
                'category' => 'Piping, Flange & Fittings',
                'contact_person' => 'Bambang Sudiro',
                'phone' => '0812-3456-7890',
                'email' => 'sales@valvenasantara.co.id',
                'address' => 'Kawasan Industri Jababeka II Blok C-14, Cikarang',
                'city' => 'Bekasi',
                'npwp' => '01.345.678.9-412.000',
                'bank_name' => 'Bank Mandiri',
                'bank_account_number' => '156-00-1428930-1',
                'bank_account_holder' => 'PT Valve & Piping Nusantara',
                'payment_terms' => 'Net 30 Days',
                'rating' => 5,
                'status' => ProcurementVendor::STATUS_ACTIVE,
                'notes' => 'Supplier resmi ASME B16.5 Class 150/300/600 Carbon Steel & Stainless Steel.',
            ],
            [
                'vendor_code' => 'VND-002',
                'name' => 'PT Hydrojet Mandiri Perkasa',
                'category' => 'Mechanical & Heavy Equipment',
                'contact_person' => 'Hendri Kurniawan',
                'phone' => '0813-9876-5432',
                'email' => 'hendri@hydrojet-mandiri.com',
                'address' => 'Jl. Mulawarman KM 16.5 Manggar',
                'city' => 'Balikpapan',
                'npwp' => '02.456.789.1-721.000',
                'bank_name' => 'Bank BCA',
                'bank_account_number' => '842-019-3388',
                'bank_account_holder' => 'PT Hydrojet Mandiri Perkasa',
                'payment_terms' => 'Net 14 Days',
                'rating' => 5,
                'status' => ProcurementVendor::STATUS_ACTIVE,
                'notes' => 'Rental UHP Water Jetting 40.000 PSI dan high pressure hoses 15 meter.',
            ],
            [
                'vendor_code' => 'VND-003',
                'name' => 'PT Sarana Safety Industri',
                'category' => 'Safety, PPE & Environmental',
                'contact_person' => 'Dewi Anggraeni',
                'phone' => '0811-2233-4455',
                'email' => 'dewi@saranasafety.co.id',
                'address' => 'Ruko Mega Grosir Cempaka Mas Blok B No. 8',
                'city' => 'Jakarta Pusat',
                'npwp' => '03.567.890.2-021.000',
                'bank_name' => 'Bank BNI',
                'bank_account_number' => '082-3344-991',
                'bank_account_holder' => 'PT Sarana Safety Industri',
                'payment_terms' => 'COD (Cash on Delivery)',
                'rating' => 4,
                'status' => ProcurementVendor::STATUS_ACTIVE,
                'notes' => 'Supplier APD K3 migas: kevlar boots, multi-gas detector MSA, full body harness.',
            ],
            [
                'vendor_code' => 'VND-004',
                'name' => 'PT Kaltim Gas & Consumables',
                'category' => 'Consumables, Welding & Chemicals',
                'contact_person' => 'Irwan Setiadi',
                'phone' => '0852-6789-0123',
                'email' => 'procurement@kaltimgas.com',
                'address' => 'Jl. Pupuk Raya Komp. Industri Kaltim No. 45',
                'city' => 'Bontang',
                'npwp' => '04.678.901.3-724.000',
                'bank_name' => 'Bank Mandiri',
                'bank_account_number' => '148-00-9871234-5',
                'bank_account_holder' => 'PT Kaltim Gas & Consumables',
                'payment_terms' => 'Net 30 Days',
                'rating' => 4,
                'status' => ProcurementVendor::STATUS_ACTIVE,
                'notes' => 'Vendor lokal gas industri argon, oksigen, dan chemical degreaser plant turnaround.',
            ],
            [
                'vendor_code' => 'VND-005',
                'name' => 'PT Cikarang Fastener & Heavy Bolting',
                'category' => 'Mechanical & Heavy Equipment',
                'contact_person' => 'Agus Prayitno',
                'phone' => '0817-5544-3322',
                'email' => 'agus@cikarangfastener.co.id',
                'address' => 'Delta Silicon Industrial Park 3 Blok F-12',
                'city' => 'Bekasi',
                'npwp' => '05.789.012.4-413.000',
                'bank_name' => 'Bank CIMB Niaga',
                'bank_account_number' => '800-1234-5678-00',
                'bank_account_holder' => 'PT Cikarang Fastener',
                'payment_terms' => '50% DP, 50% On Delivery',
                'rating' => 5,
                'status' => ProcurementVendor::STATUS_ACTIVE,
                'notes' => 'Stud bolt ASTM A193 B7 / 2H nut dan spiral wound gasket Grafoil 316SS.',
            ],
        ];

        $createdVendors = [];
        foreach ($vendors as $v) {
            $createdVendors[] = ProcurementVendor::updateOrCreate(
                ['vendor_code' => $v['vendor_code']],
                $v
            );
        }

        // 2. Purchase Requests (PR)
        $prsData = [
            [
                'pr_number' => 'PR-2026-001',
                'title' => 'Pengadaan Stud Bolt B7 & Spiral Wound Gasket Turnaround Unit 12',
                'project_id' => $project1?->id,
                'requested_by' => $requesterEmployee?->id,
                'approved_by' => $approverEmployee?->id,
                'request_date' => Carbon::now()->subDays(18),
                'required_date' => Carbon::now()->addDays(5),
                'priority' => 'urgent',
                'category' => 'Piping & Valve',
                'estimated_total' => 45000000.00,
                'status' => PurchaseRequest::STATUS_CONVERTED_TO_PO,
                'justification' => 'Kebutuhan krusial penggantian sambungan flange bolting pada shutdown reaktor.',
                'approval_notes' => 'Disetujui untuk diterbitkan PO dengan vendor PT Cikarang Fastener.',
                'created_by' => $superAdminUser?->id,
                'items' => [
                    [
                        'item_name' => 'Stud Bolt ASTM A193 Gr. B7 + 2 Nuts 2H (1" x 7")',
                        'specifications' => 'Fluoropolymer Coated Yellow, High Tensile Strength',
                        'qty' => 120,
                        'unit' => 'Set',
                        'estimated_unit_price' => 175000.00,
                        'estimated_total_price' => 21000000.00,
                    ],
                    [
                        'item_name' => 'Spiral Wound Gasket 6" Class 300 ASME B16.20',
                        'specifications' => 'SS316L Ring with Flexible Graphite Filler',
                        'qty' => 40,
                        'unit' => 'Pcs',
                        'estimated_unit_price' => 350000.00,
                        'estimated_total_price' => 14000000.00,
                    ],
                    [
                        'item_name' => 'Spiral Wound Gasket 8" Class 300 ASME B16.20',
                        'specifications' => 'SS316L Ring with Flexible Graphite Filler',
                        'qty' => 20,
                        'unit' => 'Pcs',
                        'estimated_unit_price' => 500000.00,
                        'estimated_total_price' => 10000000.00,
                    ],
                ],
            ],
            [
                'pr_number' => 'PR-2026-002',
                'title' => 'Peremajaan APD Khusus UHP Waterjetting & Safety Fall Protection',
                'project_id' => $project1?->id,
                'requested_by' => $requesterEmployee?->id,
                'approved_by' => $approverEmployee?->id,
                'request_date' => Carbon::now()->subDays(12),
                'required_date' => Carbon::now()->addDays(8),
                'priority' => 'high',
                'category' => 'Safety & PPE',
                'estimated_total' => 32500000.00,
                'status' => PurchaseRequest::STATUS_APPROVED,
                'justification' => 'Pemenuhan CSMS Pertamina & PT KPI untuk APD kru waterjet 40k psi.',
                'approval_notes' => 'Disetujui oleh HSE & PMO Manager.',
                'created_by' => $superAdminUser?->id,
                'items' => [
                    [
                        'item_name' => 'TurtleSkin WaterArmor Kevlar Gaiters (40.000 PSI Rated)',
                        'specifications' => 'CE Certified ISO 13982-1 High Pressure Protection',
                        'qty' => 6,
                        'unit' => 'Set',
                        'estimated_unit_price' => 4200000.00,
                        'estimated_total_price' => 25200000.00,
                    ],
                    [
                        'item_name' => 'Karam Full Body Harness Double Lanyard Big Hook',
                        'specifications' => 'EN361:2002 Standard with Shock Absorber',
                        'qty' => 10,
                        'unit' => 'Set',
                        'estimated_unit_price' => 730000.00,
                        'estimated_total_price' => 7300000.00,
                    ],
                ],
            ],
            [
                'pr_number' => 'PR-2026-003',
                'title' => 'Pengadaan Nozzle High Pressure Sapphire UHP 40.000 PSI',
                'project_id' => $project2?->id,
                'requested_by' => $requesterEmployee?->id,
                'approved_by' => null,
                'request_date' => Carbon::now()->subDays(3),
                'required_date' => Carbon::now()->addDays(14),
                'priority' => 'medium',
                'category' => 'Heavy Equipment & Tools',
                'estimated_total' => 18000000.00,
                'status' => PurchaseRequest::STATUS_PENDING_APPROVAL,
                'justification' => 'Cadangan operasional hydrojetting pembersihan internal tube bundle heat exchanger.',
                'approval_notes' => null,
                'created_by' => $superAdminUser?->id,
                'items' => [
                    [
                        'item_name' => 'Rotary Sapphire Nozzle 0.35mm UHP',
                        'specifications' => 'Jetstream Compatible 2800 Bar Max',
                        'qty' => 15,
                        'unit' => 'Pcs',
                        'estimated_unit_price' => 1200000.00,
                        'estimated_total_price' => 18000000.00,
                    ],
                ],
            ],
            [
                'pr_number' => 'PR-2026-004',
                'title' => 'Tabung Gas Argon Murni High Purity & Welding Wire ER70S-6',
                'project_id' => $project2?->id,
                'requested_by' => $requesterEmployee?->id,
                'approved_by' => null,
                'request_date' => Carbon::now()->subDays(1),
                'required_date' => Carbon::now()->addDays(10),
                'priority' => 'low',
                'category' => 'Consumables & Gas',
                'estimated_total' => 12500000.00,
                'status' => PurchaseRequest::STATUS_DRAFT,
                'justification' => 'Persiapan pengelasan spool pipa spool 3" dan 4".',
                'approval_notes' => null,
                'created_by' => $superAdminUser?->id,
                'items' => [
                    [
                        'item_name' => 'Gas Argon Ultra High Purity (UHP) 99.999% Tabung 6 M3',
                        'specifications' => 'Sertifikat Analisis Gas Balai Besar Pengujian',
                        'qty' => 10,
                        'unit' => 'Btl',
                        'estimated_unit_price' => 850000.00,
                        'estimated_total_price' => 8500000.00,
                    ],
                    [
                        'item_name' => 'Kawat Las Solid Wire ER70S-6 Dia 1.2mm (15kg/Roll)',
                        'specifications' => 'Kobe Steel / Kiswel AWS A5.18',
                        'qty' => 8,
                        'unit' => 'Roll',
                        'estimated_unit_price' => 500000.00,
                        'estimated_total_price' => 4000000.00,
                    ],
                ],
            ],
        ];

        $createdPRs = [];
        foreach ($prsData as $prRow) {
            $items = $prRow['items'];
            unset($prRow['items']);

            $pr = PurchaseRequest::updateOrCreate(['pr_number' => $prRow['pr_number']], $prRow);
            $pr->items()->delete();
            foreach ($items as $item) {
                $pr->items()->create($item);
            }
            $createdPRs[$pr->pr_number] = $pr;
        }

        // 3. Purchase Orders (PO)
        $posData = [
            [
                'po_number' => 'PO-2026-001',
                'purchase_request_id' => $createdPRs['PR-2026-001']->id,
                'vendor_id' => $createdVendors[4]->id, // PT Cikarang Fastener
                'project_id' => $project1?->id,
                'po_date' => Carbon::now()->subDays(15),
                'delivery_deadline' => Carbon::now()->subDays(3),
                'subtotal' => 45000000.00,
                'tax_rate' => 11.00,
                'tax_amount' => 4950000.00,
                'discount_amount' => 1000000.00,
                'grand_total' => 48950000.00,
                'payment_terms' => '50% DP, 50% On Delivery',
                'delivery_address' => 'Gudang Konsolidasi Site PT KPI Kilang Balikpapan / Bontang Terminal',
                'status' => PurchaseOrder::STATUS_COMPLETED,
                'terms_conditions' => "1. Barang harus lolos uji Mill Test Certificate (MTC).\n2. Kemasan pallet plastik bersegel.\n3. Garansi material cacat pabrik 1 tahun.",
                'issued_by' => $superAdminUser?->id,
                'notes' => 'Pesanan telah diterima utuh di site dan diverifikasi oleh QC.',
                'items' => [
                    [
                        'item_name' => 'Stud Bolt ASTM A193 Gr. B7 + 2 Nuts 2H (1" x 7")',
                        'specifications' => 'Fluoropolymer Coated Yellow, High Tensile Strength',
                        'qty' => 120,
                        'unit' => 'Set',
                        'unit_price' => 175000.00,
                        'total_price' => 21000000.00,
                        'qty_received' => 120,
                    ],
                    [
                        'item_name' => 'Spiral Wound Gasket 6" Class 300 ASME B16.20',
                        'specifications' => 'SS316L Ring with Flexible Graphite Filler',
                        'qty' => 40,
                        'unit' => 'Pcs',
                        'unit_price' => 350000.00,
                        'total_price' => 14000000.00,
                        'qty_received' => 40,
                    ],
                    [
                        'item_name' => 'Spiral Wound Gasket 8" Class 300 ASME B16.20',
                        'specifications' => 'SS316L Ring with Flexible Graphite Filler',
                        'qty' => 20,
                        'unit' => 'Pcs',
                        'unit_price' => 500000.00,
                        'total_price' => 10000000.00,
                        'qty_received' => 20,
                    ],
                ],
            ],
            [
                'po_number' => 'PO-2026-002',
                'purchase_request_id' => $createdPRs['PR-2026-002']->id,
                'vendor_id' => $createdVendors[2]->id, // PT Sarana Safety Industri
                'project_id' => $project1?->id,
                'po_date' => Carbon::now()->subDays(9),
                'delivery_deadline' => Carbon::now()->addDays(3),
                'subtotal' => 32500000.00,
                'tax_rate' => 11.00,
                'tax_amount' => 3575000.00,
                'discount_amount' => 0.00,
                'grand_total' => 36075000.00,
                'payment_terms' => 'Net 30 Days',
                'delivery_address' => 'Site Office PT ELBEMA Integra Indonesia, Area Workshop Bontang Kaltim',
                'status' => PurchaseOrder::STATUS_IN_DELIVERY,
                'terms_conditions' => 'Sertifikat CE dan uji ketahanan air 40.000 PSI harus disertakan saat pengiriman.',
                'issued_by' => $superAdminUser?->id,
                'notes' => 'Sedang dikirim via Ekspedisi Baraka Sarana Tama (In Transit).',
                'items' => [
                    [
                        'item_name' => 'TurtleSkin WaterArmor Kevlar Gaiters (40.000 PSI Rated)',
                        'specifications' => 'CE Certified ISO 13982-1 High Pressure Protection',
                        'qty' => 6,
                        'unit' => 'Set',
                        'unit_price' => 4200000.00,
                        'total_price' => 25200000.00,
                        'qty_received' => 0,
                    ],
                    [
                        'item_name' => 'Karam Full Body Harness Double Lanyard Big Hook',
                        'specifications' => 'EN361:2002 Standard with Shock Absorber',
                        'qty' => 10,
                        'unit' => 'Set',
                        'unit_price' => 730000.00,
                        'total_price' => 7300000.00,
                        'qty_received' => 0,
                    ],
                ],
            ],
            [
                'po_number' => 'PO-2026-003',
                'purchase_request_id' => null,
                'vendor_id' => $createdVendors[0]->id, // PT Valve & Piping
                'project_id' => $project2?->id,
                'po_date' => Carbon::now()->subDays(5),
                'delivery_deadline' => Carbon::now()->addDays(12),
                'subtotal' => 68000000.00,
                'tax_rate' => 11.00,
                'tax_amount' => 7480000.00,
                'discount_amount' => 2000000.00,
                'grand_total' => 73480000.00,
                'payment_terms' => 'Net 30 Days',
                'delivery_address' => 'Workshop PT ELBEMA Integra Indonesia, Kawasan Palembang, Sumsel',
                'status' => PurchaseOrder::STATUS_ISSUED,
                'terms_conditions' => 'Valve harus lolos hydrostatic test 1.5x working pressure dari pabrikan.',
                'issued_by' => $superAdminUser?->id,
                'notes' => 'PO telah diserahkan ke vendor dan dalam persiapan pabrikasi.',
                'items' => [
                    [
                        'item_name' => 'Gate Valve 4" Class 300 Flanged End RF A216 WCB',
                        'specifications' => 'API 600 Design Trim 8 OS&Y Handwheel',
                        'qty' => 4,
                        'unit' => 'Pcs',
                        'unit_price' => 12500000.00,
                        'total_price' => 50000000.00,
                        'qty_received' => 0,
                    ],
                    [
                        'item_name' => 'Check Valve 4" Class 300 Swing Type RF WCB',
                        'specifications' => 'BS 1868 Standard Bolted Cover',
                        'qty' => 2,
                        'unit' => 'Pcs',
                        'unit_price' => 9000000.00,
                        'total_price' => 18000000.00,
                        'qty_received' => 0,
                    ],
                ],
            ],
        ];

        $createdPOs = [];
        foreach ($posData as $poRow) {
            $items = $poRow['items'];
            unset($poRow['items']);

            $po = PurchaseOrder::updateOrCreate(['po_number' => $poRow['po_number']], $poRow);
            $po->items()->delete();
            foreach ($items as $item) {
                $po->items()->create($item);
            }
            $createdPOs[$po->po_number] = $po;
        }

        // 4. Logistics & Expedition Tracking
        $logisticsData = [
            [
                'delivery_number' => 'LOG-2026-001',
                'purchase_order_id' => $createdPOs['PO-2026-001']->id,
                'project_id' => $project1?->id,
                'expedition_name' => 'Karyati Ekspedisi (Sea Freight & Trucking)',
                'tracking_number' => 'KYT-BPN-998214',
                'origin' => 'Gudang Cikarang, Jawa Barat',
                'destination' => 'Site PT KPI Kilang Balikpapan / Bontang, Kaltim',
                'shipment_date' => Carbon::now()->subDays(12),
                'estimated_arrival_date' => Carbon::now()->subDays(4),
                'actual_arrival_date' => Carbon::now()->subDays(3),
                'recipient_name' => 'Surya Wijaya (Site Material Coordinator)',
                'recipient_phone' => '0812-4433-2211',
                'status' => LogisticsDelivery::STATUS_INSPECTED_AND_RECEIVED,
                'driver_name' => 'Agus Subekti',
                'driver_phone' => '0857-1234-9988',
                'vehicle_plate' => 'KT 8342 LW',
                'notes' => 'Pallet stud bolt dan gasket telah tiba lengkap tanpa cacat. Berita Acara Penerimaan (BAP) ditandatangani.',
            ],
            [
                'delivery_number' => 'LOG-2026-002',
                'purchase_order_id' => $createdPOs['PO-2026-002']->id,
                'project_id' => $project1?->id,
                'expedition_name' => 'Baraka Sarana Tama (Express Logistics)',
                'tracking_number' => 'BST-JKT-772190',
                'origin' => 'Ruko Cempaka Mas, Jakarta Pusat',
                'destination' => 'Site Office PT ELBEMA, Workshop Bontang, Kaltim',
                'shipment_date' => Carbon::now()->subDays(3),
                'estimated_arrival_date' => Carbon::now()->addDays(2),
                'actual_arrival_date' => null,
                'recipient_name' => 'Randi Pratama (HSE Officer Site)',
                'recipient_phone' => '0813-7788-9900',
                'status' => LogisticsDelivery::STATUS_IN_TRANSIT,
                'driver_name' => 'M. Taufik',
                'driver_phone' => '0819-0099-8877',
                'vehicle_plate' => 'B 9210 UXA',
                'notes' => 'Posisi kargo saat ini di Pelabuhan Semayang Balikpapan bersiap clearance ke Bontang.',
            ],
            [
                'delivery_number' => 'LOG-2026-003',
                'purchase_order_id' => null,
                'project_id' => $project2?->id,
                'expedition_name' => 'Internal Armada PT ELBEMA (Isuzu Giga Box)',
                'tracking_number' => 'INT-ELB-2026-04',
                'origin' => 'Workshop Pusat Cikarang',
                'destination' => 'Site Pertamina Refinery Plaju, Palembang',
                'shipment_date' => Carbon::now()->subDays(1),
                'estimated_arrival_date' => Carbon::now()->addDays(1),
                'actual_arrival_date' => null,
                'recipient_name' => 'Dedi Iskandar (Field Supervisor)',
                'recipient_phone' => '0821-6655-4433',
                'status' => LogisticsDelivery::STATUS_IN_TRANSIT,
                'driver_name' => 'Darmawan Santoso',
                'driver_phone' => '0812-7711-3322',
                'vehicle_plate' => 'B 9801 WDC',
                'notes' => 'Mobilisasi perlengkapan uji kalibrasi hydrotest dan power pack pump.',
            ],
        ];

        foreach ($logisticsData as $logRow) {
            LogisticsDelivery::updateOrCreate(['delivery_number' => $logRow['delivery_number']], $logRow);
        }
    }
}
