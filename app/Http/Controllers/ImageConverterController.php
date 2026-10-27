<?php

namespace App\Http\Controllers;

use App\Services\Image\WebpConversionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class ImageConverterController extends Controller
{
    public function index()
    {
        return view('pages.image-converter.index', [
            'maxFileSizeKb' => (int) config('image-converter.max_file_size_kb', 5120),
            'maxFilesPerBatch' => (int) config('image-converter.max_files_per_batch', 20),
        ]);
    }

    public function convert(Request $request, WebpConversionService $converter): JsonResponse
    {
        $files = $request->file('images', []);

        if (! is_array($files) || $files === []) {
            return response()->json(['message' => 'Pilih minimal satu gambar untuk dikonversi.'], 422);
        }

        $batch = (string) Str::uuid();
        $maxFiles = (int) config('image-converter.max_files_per_batch', 20);
        $maxSize = (int) config('image-converter.max_file_size_kb', 5120);
        $relativeDirectory = $this->batchDirectory($request, $batch);
        $results = [];
        $successfulFiles = [];

        foreach ($files as $index => $file) {
            $originalName = $file?->getClientOriginalName() ?: 'gambar-'.($index + 1);
            $originalSize = $file?->getSize() ?: 0;

            if ($index >= $maxFiles) {
                $results[] = $this->failedResult($originalName, $originalSize, "Maksimal {$maxFiles} gambar per proses.");
                continue;
            }

            $validator = Validator::make(
                ['image' => $file],
                ['image' => ['required', 'file', 'mimes:jpg,jpeg,png', "max:{$maxSize}"]],
                [
                    'image.required' => 'File tidak dapat dibaca.',
                    'image.file' => 'Item yang dipilih bukan file yang valid.',
                    'image.mimes' => 'Format harus JPG, JPEG, atau PNG.',
                    'image.max' => 'Ukuran file melebihi '.number_format($maxSize / 1024, 0).' MB.',
                ]
            );

            if ($validator->fails()) {
                $results[] = $this->failedResult($originalName, $originalSize, $validator->errors()->first('image'));
                continue;
            }

            $outputName = $this->availableOutputName($relativeDirectory, $originalName);
            $relativePath = $relativeDirectory.'/'.$outputName;

            try {
                $converter->convert($file->getRealPath(), Storage::disk('local')->path($relativePath));
                $outputSize = Storage::disk('local')->size($relativePath);
                $successfulFiles[] = $outputName;
                $results[] = [
                    'name' => $originalName,
                    'original_size' => $originalSize,
                    'status' => 'success',
                    'message' => 'Berhasil dikonversi.',
                    'output_name' => $outputName,
                    'output_size' => $outputSize,
                    'download_url' => URL::temporarySignedRoute(
                        'dashboard-admin.image-converter.download',
                        now()->addHours(2),
                        ['batch' => $batch, 'filename' => $outputName]
                    ),
                    'preview_url' => URL::temporarySignedRoute(
                        'dashboard-admin.image-converter.preview',
                        now()->addHours(2),
                        ['batch' => $batch, 'filename' => $outputName]
                    ),
                ];
            } catch (\Throwable $exception) {
                report($exception);
                $results[] = $this->failedResult($originalName, $originalSize, 'Gambar gagal dikonversi. Pastikan file tidak rusak.');
            }
        }

        return response()->json([
            'message' => count($successfulFiles).' dari '.count($files).' gambar berhasil dikonversi.',
            'results' => $results,
            'download_all_url' => $successfulFiles === [] ? null : URL::temporarySignedRoute(
                'dashboard-admin.image-converter.download-all',
                now()->addHours(2),
                ['batch' => $batch]
            ),
        ]);
    }

    public function preview(Request $request, string $batch, string $filename): BinaryFileResponse
    {
        $path = $this->authorizedResultPath($request, $batch, $filename);

        return response()->file($path, ['Content-Type' => 'image/webp']);
    }

    public function download(Request $request, string $batch, string $filename): BinaryFileResponse
    {
        return response()->download($this->authorizedResultPath($request, $batch, $filename), $filename);
    }

    public function downloadAll(Request $request, string $batch): BinaryFileResponse
    {
        abort_unless(Str::isUuid($batch), 404);

        $directory = $this->batchDirectory($request, $batch);
        $files = collect(Storage::disk('local')->files($directory))
            ->filter(fn (string $path) => Str::endsWith(Str::lower($path), '.webp'));

        abort_if($files->isEmpty(), 404);

        $zipPath = Storage::disk('local')->path($directory.'/hasil-webp.zip');
        $zip = new ZipArchive();
        abort_unless($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true, 500, 'ZIP tidak dapat dibuat.');

        foreach ($files as $file) {
            $zip->addFile(Storage::disk('local')->path($file), basename($file));
        }

        $zip->close();

        return response()->download($zipPath, 'hasil-webp-'.$batch.'.zip')->deleteFileAfterSend(true);
    }

    private function authorizedResultPath(Request $request, string $batch, string $filename): string
    {
        abort_unless(Str::isUuid($batch), 404);
        abort_unless($filename === basename($filename) && Str::endsWith(Str::lower($filename), '.webp'), 404);

        $relativePath = $this->batchDirectory($request, $batch).'/'.$filename;
        abort_unless(Storage::disk('local')->exists($relativePath), 404);

        return Storage::disk('local')->path($relativePath);
    }

    private function batchDirectory(Request $request, string $batch): string
    {
        return 'image-converter/'.$request->user()->getAuthIdentifier().'/'.$batch;
    }

    private function availableOutputName(string $directory, string $originalName): string
    {
        $baseName = pathinfo(basename($originalName), PATHINFO_FILENAME);
        $baseName = trim((string) preg_replace('/[\\x00-\\x1F\\x7F\\\\\/\:\*\?"<>\|]+/u', '-', $baseName), '. ');
        $baseName = $baseName !== '' ? $baseName : 'gambar';
        $candidate = $baseName.'.webp';
        $suffix = 2;

        while (Storage::disk('local')->exists($directory.'/'.$candidate)) {
            $candidate = $baseName.'-'.$suffix.'.webp';
            $suffix++;
        }

        return $candidate;
    }

    private function failedResult(string $name, int $size, string $message): array
    {
        return [
            'name' => $name,
            'original_size' => $size,
            'status' => 'failed',
            'message' => $message,
        ];
    }
}
