<?php

namespace App\Console\Commands;

use App\Models\Database\VisitorSetting;
use App\Services\Visitor\VisitorCleanupService;
use Illuminate\Console\Command;
use Throwable;

class CleanupVisitors extends Command
{
    protected $signature = 'visitors:cleanup
        {--from= : Tanggal awal pembersihan (YYYY-MM-DD)}
        {--to= : Tanggal akhir pembersihan (YYYY-MM-DD)}
        {--force : Abaikan status auto-delete dan batas retensi}
        {--dry-run : Tampilkan estimasi tanpa menghapus data}';

    protected $description = 'Menghapus raw visitor yang retensinya lewat dan telah diarsipkan';

    public function handle(VisitorCleanupService $cleanupService): int
    {
        $settings = VisitorSetting::current();

        if (! $settings->auto_delete_enabled && ! $this->option('force')) {
            $this->warn('Penghapusan otomatis nonaktif. Gunakan --force untuk menjalankan manual.');

            return self::SUCCESS;
        }

        $from = $this->option('from');
        $to = $this->option('to');

        if (($from && ! $to) || ($to && ! $from)) {
            $this->error('Opsi --from dan --to harus digunakan bersama.');

            return self::INVALID;
        }

        try {
            $result = $cleanupService->cleanup(
                $settings,
                $from,
                $to,
                (bool) $this->option('force'),
                (bool) $this->option('dry-run'),
            );

            $this->table(
                ['Rentang', 'Kandidat', 'Belum diarsipkan', 'Dihapus', 'Status'],
                [[
                    ($result['range_start'] ?? '-').' s/d '.($result['range_end'] ?? '-'),
                    $result['candidate_count'],
                    $result['unarchived_records'],
                    $result['deleted_count'],
                    $result['status'],
                ]]
            );

            return self::SUCCESS;
        } catch (Throwable $exception) {
            report($exception);
            $this->error($exception->getMessage());

            return self::FAILURE;
        }
    }
}
