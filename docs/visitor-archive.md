# Arsip dan Pembersihan Visitor

Fitur ini meringkas data mentah pada tabel `visitors` ke `visitor_archives`. Arsip hanya menyimpan statistik agregat per periode dan breakdown angka harian; IP, user agent, session, dan data identitas lain tidak disalin.

## Alur proses

1. Scheduler menjalankan `visitors:process` setiap menit.
2. Command membaca satu konfigurasi aktif dari `visitor_settings` dan memeriksa hari, jam, interval, serta timezone.
3. Hanya periode kalender yang sudah selesai yang boleh diarsipkan.
4. Service menghitung total kunjungan, unique visitor berdasarkan IP yang tersedia, dan page view berdasarkan URL di dalam transaction serta lock.
5. Unique constraint dan pemeriksaan irisan mencegah periode ganda atau tumpang tindih.
6. Cleanup memilih raw visitor yang melewati retensi dan tercakup arsip berstatus `completed`. Record yang belum diarsipkan selalu dilewati.
7. Jika `delete_after_archive` dan `auto_delete_enabled` aktif, raw data periode yang baru berhasil diarsipkan langsung dibersihkan.

## Command manual

```bash
php artisan visitors:archive --dry-run
php artisan visitors:archive --from=2026-07-01 --to=2026-07-07 --dry-run
php artisan visitors:archive --from=2026-07-01 --to=2026-07-07 --force

php artisan visitors:cleanup --dry-run --force
php artisan visitors:cleanup --from=2026-07-01 --to=2026-07-07 --dry-run --force
php artisan visitors:cleanup --from=2026-07-01 --to=2026-07-07 --force

php artisan visitors:process --dry-run --force
php artisan visitors:process --force
```

`--dry-run` tidak mengubah database. `--force` hanya melewati status/jadwal/retensi sesuai command; perlindungan periode aktif, arsip yang tumpang tindih, dan data yang belum diarsipkan tetap berlaku.

## Cron Linux / hosting

Laravel Scheduler memerlukan satu cron setiap menit:

```cron
* * * * * php /path/to/project/artisan schedule:run >> /dev/null 2>&1
```

Pada cPanel, buka **Cron Jobs**, pilih interval **Once Per Minute**, lalu gunakan path PHP dan project yang absolut. Contoh umum:

```cron
* * * * * /usr/local/bin/php /home/username/public_html/artisan schedule:run >> /dev/null 2>&1
```

Path binary PHP dapat berbeda menurut hosting. Gunakan versi PHP yang sama dengan aplikasi (minimal PHP 8.2) dan pastikan cron dijalankan dari user yang memiliki akses tulis ke `storage` dan `bootstrap/cache`.

Scheduler sudah memakai `withoutOverlapping()` dan `onOneServer()`. Karena itu cache store production harus mendukung atomic lock; konfigurasi default project menggunakan database cache.

## Permission admin

- `view visitor statistics`
- `manage visitor settings`
- `run visitor archive`
- `delete visitor data`

Jalankan seeder berikut setelah deployment, lalu berikan permission yang diperlukan ke role admin:

```bash
php artisan db:seed --class=VisitorPermissionsSeeder --force
```
