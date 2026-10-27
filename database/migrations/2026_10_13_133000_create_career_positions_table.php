<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('career_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_id')->nullable()->constrained('careers')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->string('department')->nullable();
            $table->string('type')->nullable(); // e.g. Full Time, Part Time, Contract, Internship, Remote
            $table->string('location')->nullable(); // e.g. Jakarta, Hybrid, Remote
            $table->text('description')->nullable();
            $table->text('requirements')->nullable();
            $table->string('salary_range')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed initial positions based on existing career content
        $career = DB::table('careers')->find(1);
        $careerId = $career ? $career->id : null;

        $positions = [
            [
                'career_id' => $careerId,
                'title' => 'Senior Fullstack Developer (Laravel & Vue/React)',
                'slug' => 'senior-fullstack-developer',
                'department' => 'Engineering & IT',
                'type' => 'Full Time',
                'location' => 'Jakarta (Hybrid)',
                'description' => '<p>Bertanggung jawab atas arsitektur sistem web enterprise, pembuatan dan integrasi modul ERP berkinerja tinggi, API gateway, serta kolaborasi erat bersama tim produk dan infrastruktur cloud.</p>',
                'requirements' => '<ul><li>Pengalaman minimal 3 tahun dengan framework PHP Laravel & modern Javascript (Vue.js/React).</li><li>Menguasai desain basis data relasional (MySQL/PostgreSQL) dan optimasi query.</li><li>Memahami RESTful API, caching (Redis), dan version control Git.</li><li>Kemampuan pemecahan masalah yang baik serta terbiasa bekerja dalam tim Scrum/Agile.</li></ul>',
                'salary_range' => 'Kompetitif',
                'order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'career_id' => $careerId,
                'title' => 'UI/UX Designer & Product Specialist',
                'slug' => 'ui-ux-designer-product-specialist',
                'department' => 'Product & Design',
                'type' => 'Full Time',
                'location' => 'Jakarta (On-site / Hybrid)',
                'description' => '<p>Merancang antarmuka pengguna yang estetis, modern, dan fungsional untuk sistem dashboard enterprise ERP. Melakukan riset pengguna, visual prototype di Figma, serta standardisasi design system.</p>',
                'requirements' => '<ul><li>Pengalaman minimal 2 tahun sebagai UI/UX Designer untuk aplikasi enterprise atau SaaS B2B.</li><li>Keahlian mendalam menggunakan Figma, prototyping interaktif, dan pemeliharaan Design System.</li><li>Mampu menganalisis alur bisnis yang kompleks menjadi pengalaman antarmuka yang intuitif.</li><li>Menyertakan portofolio desain UI/UX terkini.</li></ul>',
                'salary_range' => 'Kompetitif',
                'order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'career_id' => $careerId,
                'title' => 'ERP Implementation Specialist',
                'slug' => 'erp-implementation-specialist',
                'department' => 'Operations & Consulting',
                'type' => 'Full Time',
                'location' => 'Jakarta & Client Site',
                'description' => '<p>Memimpin proses analisis kebutuhan bisnis klien, pemetaan proses bisnis manufaktur, supply chain, dan keuangan ke dalam sistem Total ERP, serta mengawal proses training user dan deployment go-live.</p>',
                'requirements' => '<ul><li>Pengalaman minimal 2 tahun dalam implementasi sistem ERP enterprise (SAP, Odoo, Oracle, atau custom ERP).</li><li>Pemahaman mendalam mengenai alur bisnis akuntansi, inventory, procurement, atau manufaktur.</li><li>Kemampuan komunikasi interpersonal, presentasi, dan problem solving yang sangat baik.</li><li>Bersedia melakukan kunjungan implementasi di lokasi klien bila diperlukan.</li></ul>',
                'salary_range' => 'Kompetitif',
                'order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('career_positions')->insert($positions);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_positions');
    }
};
