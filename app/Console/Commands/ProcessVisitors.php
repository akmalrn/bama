<?php

namespace App\Console\Commands;

use App\Models\Database\VisitorSetting;
use App\Services\Visitor\VisitorArchiveService;
use App\Services\Visitor\VisitorCleanupService;
use App\Services\Visitor\VisitorPeriodService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessVisitors extends Command
{
    protected $signature = 'visitors:process
        {--force : Abaikan pemeriksaan waktu dan status otomatis}
        {--dry-run : Tampilkan rencana tanpa mengubah database}';

    protected $description = 'Menjalankan arsip visitor terjadwal dan pembersihan retensi';

    public function handle(
        VisitorPeriodService $periodService,
        VisitorArchiveService $archiveService,
        VisitorCleanupService $cleanupService,
    ): int {
        $settings = VisitorSetting::current();
        $force = (bool) $this->option('force');
        $dryRun = (bool) $this->option('dry-run');

        if (! $force && ! $periodService->isDue($settings)) {
            $this->line('Belum masuk jadwal proses visitor atau periode sudah diarsipkan.');

            return self::SUCCESS;
        }

        try {
            $archived = 0;
            $skipped = 0;

            if ($settings->archive_enabled || $force) {
                foreach ($periodService->pendingCompletedPeriods($settings) as $period) {
                    try {
                        $result = $archiveService->archive(
                            $period['from'],
                            $period['to'],
                            $period['interval'],
                            $dryRun,
                        );

                        in_array($result['status'], ['archived', 'dry_run'], true) ? $archived++ : $skipped++;
                    } catch (\DomainException $exception) {
                        $skipped++;
                        Log::notice('Visitor archive period skipped.', [
                            'period_start' => $period['from']->toDateString(),
                            'period_end' => $period['to']->toDateString(),
                            'reason' => $exception->getMessage(),
                        ]);
                    }
                }
            }

            $cleanup = null;

            if ($settings->auto_delete_enabled || $force) {
                $cleanup = $cleanupService->cleanup($settings, force: false, dryRun: $dryRun);
            }

            Log::info('Scheduled visitor process completed.', [
                'archives_processed' => $archived,
                'archives_skipped' => $skipped,
                'cleanup_candidates' => $cleanup['candidate_count'] ?? 0,
                'dry_run' => $dryRun,
            ]);

            $this->info("Proses selesai: {$archived} periode diproses, {$skipped} dilewati.");

            if ($cleanup) {
                $this->line('Kandidat cleanup: '.$cleanup['candidate_count'].'; dihapus: '.$cleanup['deleted_count'].'.');
            }

            return self::SUCCESS;
        } catch (Throwable $exception) {
            Log::error('Scheduled visitor process failed.', ['exception' => $exception]);
            $this->error($exception->getMessage());

            return self::FAILURE;
        }
    }
}
