<?php

namespace App\Services\Image;

use Intervention\Image\ImageManager;
use RuntimeException;

class WebpConversionService
{
    public function convert(string $sourcePath, string $destinationPath, ?int $quality = null): void
    {
        $destinationDirectory = dirname($destinationPath);

        if (! is_dir($destinationDirectory) && ! mkdir($destinationDirectory, 0755, true) && ! is_dir($destinationDirectory)) {
            throw new RuntimeException('Folder hasil konversi tidak dapat dibuat.');
        }

        $encodedImage = ImageManager::gd()
            ->read($sourcePath)
            ->toWebp($quality ?? (int) config('image-converter.quality', 80));

        if (file_put_contents($destinationPath, (string) $encodedImage) === false) {
            throw new RuntimeException('File WebP tidak dapat disimpan.');
        }
    }
}
