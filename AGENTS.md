# Panduan Agent AI — Manajemen ERP

Dokumen ini adalah konteks kerja utama bagi agent AI yang mengubah repository ini. Baca dokumen ini sebelum mengedit kode, lalu periksa file terkait karena implementasi aktual tetap menjadi sumber kebenaran.

## Ringkasan proyek

Proyek ini adalah aplikasi company profile dan CMS/admin berbasis Laravel 11. Situs publik menampilkan profil perusahaan, layanan, produk, proyek, galeri, acara/promosi, blog, dan kontak. Area admin dipakai untuk mengelola konten, pengguna, role/permission, statistik visitor, serta utilitas gambar.

- Backend: PHP 8.2+, Laravel 11, Eloquent ORM.
- UI: Blade, CSS/JavaScript statis, Vite 5.
- Otorisasi: `spatie/laravel-permission`.
- Impersonasi user: `bnbwebexpertise/laravel-impersonate`.
- Pemrosesan gambar: `intervention/image`; upload tertentu dikonversi ke WebP.
- Email: Resend.
- Database default development: SQLite (`database/database.sqlite`); deployment dapat memakai database lain melalui `.env`.
- Bahasa konten dan pesan aplikasi dominan Bahasa Indonesia.

## Peta direktori

```text
app/
├── Console/Commands/       # command sitemap serta proses/arsip/cleanup visitor
├── Http/
│   ├── Controllers/
│   │   ├── Auth/           # login dan logout
│   │   └── Database/       # CMS CRUD dan controller frontend publik
│   ├── Middleware/         # TrackVisitor
│   └── Requests/           # Form Request khusus
├── Mail/                   # email formulir kontak
├── Models/
│   └── Database/           # model konten/CMS
├── Providers/              # bootstrap aplikasi dan Gate global
├── Services/
│   ├── Image/              # konversi WebP
│   └── Visitor/            # statistik, periode, arsip, dan cleanup
└── Support/                # helper/trait lintas fitur

bootstrap/app.php           # route, alias middleware, TrackVisitor, exception handler
config/                     # konfigurasi Laravel/package
database/
├── factories/
├── migrations/             # sumber kebenaran skema database
└── seeders/                # data awal dan permission
docs/                       # dokumentasi fitur khusus
public/
├── assets/                 # asset template dan upload yang disajikan publik
├── seeders/                # asset seed; pertahankan jika masih direferensikan
└── index.php
resources/
├── css/app.css             # entry Vite CSS
├── js/app.js               # entry Vite JavaScript
└── views/
    ├── frontend/           # halaman situs publik dan komponennya
    ├── pages/              # halaman CMS/admin
    ├── layout/             # layout Blade bersama
    └── components/         # komponen Blade bersama
routes/
├── web.php                 # route situs publik, autentikasi, admin, dan demo template
├── api.php
└── console.php             # scheduler sitemap dan visitor
storage/                    # log/cache/file runtime; jangan commit hasil runtime
tests/                      # lokasi standar untuk Feature dan Unit test
```

`vendor/`, `storage/`, file cache, hasil build, dan file upload/runtime bukan tempat untuk implementasi fitur aplikasi.

## Arsitektur dan alur request

### Situs publik

Route publik berada di bagian akhir `routes/web.php` dan diarahkan ke `App\Http\Controllers\Database\FrontendController`. Controller mengambil data Eloquent lalu merender `resources/views/frontend/*`. Detail konten memakai slug, misalnya produk, layanan, proyek, event, dan blog.

Alur umum:

```text
routes/web.php
  → FrontendController
  → App\Models\Database\*
  → resources/views/frontend/*.blade.php
  → public/assets atau file upload publik
```

### CMS/admin

Semua fitur utama admin berada di group route berprefix `dashboard-admin`, bernama `dashboard-admin.*`, dan dilindungi middleware `auth`. CRUD umumnya menggunakan resource controller dalam `app/Http/Controllers/Database` dan view di `resources/views/pages/<fitur>`.

Saat menambah fitur CMS, sinkronkan seluruh lapisan berikut:

1. Migration dan relasi database.
2. Model `$fillable`, `$casts`, relasi, dan slug bila diperlukan.
3. Controller: validasi, transaksi, file cleanup, dan redirect bernama.
4. Route admin serta middleware permission.
5. View index/create/edit dan partial yang dipakai bersama.
6. Seeder permission jika fitur memerlukan ability baru.
7. Tampilan/query frontend jika konten dipublikasikan.
8. Test yang mencakup happy path, validasi, dan otorisasi.

### Data konfigurasi satu-record

Beberapa section/label adalah konfigurasi singleton dan memakai pola `Model::first()` atau `updateOrCreate(['id' => 1], ...)`. Pertahankan pola tersebut bila mengubah section yang sudah ada; jangan mengubahnya menjadi koleksi tanpa kebutuhan bisnis yang jelas.

### Autentikasi dan otorisasi

- Login admin: route bernama `login` pada `/Get-Login-AdMiN`.
- Semua halaman operasional admin harus tetap berada di middleware `auth`.
- Alias `role`, `permission`, dan `role_or_permission` didaftarkan di `bootstrap/app.php`.
- Role `God Admin` mendapat akses global melalui `Gate::before()` di `AppServiceProvider`.
- Permission berbahasa Indonesia sudah dipakai pada banyak CRUD; permission visitor memakai bahasa Inggris. Ikuti kosakata fitur yang sudah ada dan jangan mengganti nama permission lama tanpa migrasi/seeder serta strategi kompatibilitas.

### Upload dan gambar

- Validasi MIME dan ukuran sesuai kebutuhan sebelum menyimpan file.
- Gunakan `App\Services\Image\WebpConversionService` untuk alur yang sudah mengonversi gambar ke WebP.
- Simpan path relatif yang konsisten dengan data lama.
- Saat mengganti atau menghapus record, hapus file lama hanya setelah target terverifikasi dan pertimbangkan kegagalan transaction.
- Jangan mengedit hasil upload atau asset biner secara massal tanpa permintaan eksplisit.

### Visitor dan scheduler

`TrackVisitor` dipasang sebagai middleware global. Scheduler di `routes/console.php` menjalankan pembuatan sitemap dan pemrosesan visitor. Detail retensi, archive, cleanup, lock, serta command manual ada di `docs/visitor-archive.md`.

Perubahan fitur visitor wajib menjaga:

- periode aktif tidak boleh diarsipkan;
- archive tidak boleh tumpang tindih;
- raw record yang belum diarsipkan tidak boleh dihapus;
- transaction, lock, unique constraint, dan mode `--dry-run`;
- kompatibilitas scheduler `withoutOverlapping()` dan `onOneServer()`.

## Konvensi implementasi

- Ikuti PSR-12 dan gaya Laravel 11; format PHP dengan Pint.
- Gunakan route bernama daripada URL hard-coded di controller dan Blade.
- Gunakan route model binding bila pola fitur sudah mendukungnya.
- Validasi semua input sebelum operasi database/file. Untuk aturan besar atau reusable, gunakan Form Request.
- Gunakan eager loading untuk relasi yang dirender dalam loop agar tidak menimbulkan N+1 query.
- Gunakan `DB::transaction()` untuk perubahan beberapa tabel yang harus atomik.
- Field checkbox harus dibaca dengan `$request->boolean(...)` agar nilai tidak terkirim diperlakukan benar.
- Escape output Blade dengan `{{ }}`. Gunakan `{!! !!}` hanya untuk HTML tepercaya/tersanitasi seperti konten editor yang memang dirancang menyimpan HTML.
- Pertahankan istilah dan ejaan route/database lama walaupun tidak ideal (contoh `categorys-*`, `portofolios`, `wrokshops`, `list-of-benerfit-*`) kecuali tugas secara eksplisit mencakup migrasi kompatibel.
- Jangan memasukkan secret dari `.env` ke source control, log, test fixture, atau respons.
- Jangan mengubah file di `vendor/`; ubah kode aplikasi atau konfigurasi package.

## Menjalankan proyek

Setup development standar:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev
php artisan serve
```

Pada Windows, salin `.env.example` ke `.env` dengan perintah shell yang sesuai. Pastikan ekstensi PHP yang dibutuhkan aktif dan `storage/` serta `bootstrap/cache/` dapat ditulis.

Worker/scheduler lokal bila fitur terkait membutuhkannya:

```bash
php artisan queue:work
php artisan schedule:work
```

## Verifikasi sebelum menyerahkan perubahan

Pilih pemeriksaan yang relevan dengan perubahan dan laporkan hasilnya:

```bash
php artisan test
./vendor/bin/pint --test
npm run build
php artisan route:list
php artisan config:clear
php artisan view:clear
```

Untuk perubahan database, jalankan test pada database terisolasi dan periksa migrasi maju/mundur. Jangan menjalankan `migrate:fresh`, seeder destruktif, cleanup visitor tanpa `--dry-run`, atau penghapusan data pada database pengguna kecuali diminta secara eksplisit.

Checklist manual untuk perubahan UI:

- halaman publik dan admin tidak menghasilkan error;
- tampilan desktop dan mobile tetap terbaca;
- state kosong, validasi gagal, dan pagination tetap benar;
- upload, penggantian, dan penghapusan gambar berjalan konsisten;
- akses tanpa login atau tanpa permission ditolak;
- metadata/slug dan link detail tetap valid.

## Aturan kerja agent

1. Mulai dengan membaca `AGENTS.md`, file fitur terkait, migration, dan route sebelum mengubah kode.
2. Periksa perubahan lokal yang sudah ada dan jangan menimpa pekerjaan pengguna.
3. Buat perubahan sekecil mungkin yang menyelesaikan tugas; hindari refactor lintas fitur tanpa kebutuhan.
4. Jangan mengubah skema database hanya dari asumsi model—cek migration terbaru dan data compatibility.
5. Jika menambah kolom/tabel, buat migration baru; jangan mengedit migration lama yang mungkin sudah dijalankan di production.
6. Untuk operasi file/data yang destruktif, identifikasi target secara eksplisit dan sediakan jalur aman atau dry-run.
7. Tambahkan atau perbarui dokumentasi di `docs/` untuk alur operasional, scheduler, integrasi eksternal, atau keputusan yang tidak jelas dari kode.
8. Setelah implementasi, jalankan verifikasi proporsional dan sebutkan pemeriksaan yang belum dapat dijalankan.

## File rujukan utama

- `routes/web.php`: daftar route dan boundary publik/admin.
- `routes/console.php`: jadwal command.
- `bootstrap/app.php`: middleware dan exception behavior.
- `app/Http/Controllers/Database/FrontendController.php`: query dan komposisi halaman publik.
- `app/Http/Controllers/Database/*Controller.php`: pola CRUD CMS.
- `app/Models/Database/`: relasi dan cast domain konten.
- `database/migrations/`: definisi skema faktual.
- `database/seeders/PermissionsSeeder.php`: permission CMS.
- `docs/visitor-archive.md`: prosedur aman data visitor.

