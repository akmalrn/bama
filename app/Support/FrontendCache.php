<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FrontendCache
{
    /**
     * Durasi cache dalam detik (60 menit = 3600 detik).
     */
    public const TTL = 3600;

    /**
     * Menghapus cache seluruh halaman frontend.
     */
    public static function clearAll(): void
    {
        // 1. Jika driver cache database atau terdapat tabel cache, bersihkan semua key yang mengandung 'frontend:'
        try {
            if (Schema::hasTable('cache')) {
                DB::table('cache')->where('key', 'like', '%frontend:%')->delete();
            }
        } catch (\Throwable $e) {
            // Abaikan jika database offline atau belum termigrasi
        }

        // 2. Hapus kunci-kunci utama secara eksplisit melalui Cache facade
        // (bekerja untuk semua driver: file, redis, array, database, memcached)
        $knownKeys = [
            'frontend:layout_shared',
            'frontend:home',
            'frontend:about',
            'frontend:career',
            'frontend:collaboration',
            'frontend:galleries',
            'frontend:contact',
            'frontend:portfolios',
            'frontend:comparison:total-vs-zahir',
        ];

        foreach ($knownKeys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Otomatis invalidate cache berdasarkan model yang di-insert, di-update, atau di-delete.
     */
    public static function invalidateForModel(Model $model): void
    {
        $slug = $model->slug ?? null;
        $originalSlug = $model->getOriginal('slug') ?? null;

        if ($slug) {
            Cache::forget("frontend:service:{$slug}");
            Cache::forget("frontend:product:{$slug}");
            Cache::forget("frontend:category_product:{$slug}");
            Cache::forget("frontend:business_field:{$slug}");
            Cache::forget("frontend:project:{$slug}");
            Cache::forget("frontend:comparison:{$slug}");
            Cache::forget("frontend:blog:{$slug}");
            Cache::forget("frontend:event_promotion:{$slug}");
        }

        if ($originalSlug && $originalSlug !== $slug) {
            Cache::forget("frontend:service:{$originalSlug}");
            Cache::forget("frontend:product:{$originalSlug}");
            Cache::forget("frontend:category_product:{$originalSlug}");
            Cache::forget("frontend:business_field:{$originalSlug}");
            Cache::forget("frontend:project:{$originalSlug}");
            Cache::forget("frontend:comparison:{$originalSlug}");
            Cache::forget("frontend:blog:{$originalSlug}");
            Cache::forget("frontend:event_promotion:{$originalSlug}");
        }

        static::clearAll();
    }

    public static function forgetHome(): void
    {
        Cache::forget('frontend:home');
    }

    public static function forgetAbout(): void
    {
        Cache::forget('frontend:about');
    }

    public static function forgetService(?string $slug = null): void
    {
        Cache::forget('frontend:home');
        Cache::forget('frontend:layout_shared');
        Cache::forget('frontend:contact');
        if ($slug) {
            Cache::forget("frontend:service:{$slug}");
        }
    }

    public static function forgetProduct(?string $slug = null): void
    {
        Cache::forget('frontend:home');
        Cache::forget('frontend:layout_shared');
        if ($slug) {
            Cache::forget("frontend:product:{$slug}");
        }
    }

    public static function forgetCategoryProduct(?string $slug = null): void
    {
        Cache::forget('frontend:home');
        Cache::forget('frontend:layout_shared');
        if ($slug) {
            Cache::forget("frontend:category_product:{$slug}");
        }
    }

    public static function forgetBusinessField(?string $slug = null): void
    {
        Cache::forget('frontend:home');
        if ($slug) {
            Cache::forget("frontend:business_field:{$slug}");
        }
    }

    public static function forgetProject(?string $slug = null): void
    {
        Cache::forget('frontend:layout_shared');
        if ($slug) {
            Cache::forget("frontend:project:{$slug}");
            Cache::forget("frontend:comparison:{$slug}");
        }
        Cache::forget('frontend:comparison:total-vs-zahir');
    }

    public static function forgetBlog(?string $slug = null): void
    {
        if ($slug) {
            Cache::forget("frontend:blog:{$slug}");
            Cache::forget("frontend:event_promotion:{$slug}");
        }
    }

    public static function forgetGalleries(): void
    {
        Cache::forget('frontend:home');
        Cache::forget('frontend:galleries');
    }

    public static function forgetContact(): void
    {
        Cache::forget('frontend:home');
        Cache::forget('frontend:layout_shared');
        Cache::forget('frontend:about');
        Cache::forget('frontend:career');
        Cache::forget('frontend:collaboration');
        Cache::forget('frontend:contact');
    }

    public static function forgetShared(): void
    {
        Cache::forget('frontend:layout_shared');
    }
}
