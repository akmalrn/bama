<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;

class VisitorSetting extends Model
{
    protected $fillable = [
        'singleton_key',
        'archive_enabled',
        'archive_interval',
        'archive_day',
        'archive_month_day',
        'archive_time',
        'retention_value',
        'retention_unit',
        'auto_delete_enabled',
        'delete_after_archive',
        'timezone',
        'last_archived_at',
        'last_cleanup_at',
    ];

    protected function casts(): array
    {
        return [
            'archive_enabled' => 'boolean',
            'auto_delete_enabled' => 'boolean',
            'delete_after_archive' => 'boolean',
            'archive_day' => 'integer',
            'archive_month_day' => 'integer',
            'retention_value' => 'integer',
            'last_archived_at' => 'datetime',
            'last_cleanup_at' => 'datetime',
        ];
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate(
            ['singleton_key' => 1],
            static::defaults()
        );
    }

    public static function defaults(): array
    {
        return [
            'archive_enabled' => true,
            'archive_interval' => 'weekly',
            'archive_day' => 1,
            'archive_month_day' => 1,
            'archive_time' => '00:10:00',
            'retention_value' => 30,
            'retention_unit' => 'days',
            'auto_delete_enabled' => false,
            'delete_after_archive' => false,
            'timezone' => config('app.timezone', 'Asia/Jakarta'),
        ];
    }
}
