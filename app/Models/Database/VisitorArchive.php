<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VisitorArchive extends Model
{
    protected $fillable = [
        'period_start',
        'period_end',
        'archive_interval',
        'total_visits',
        'unique_visitors',
        'total_pages_viewed',
        'daily_breakdown',
        'status',
        'archived_at',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'total_visits' => 'integer',
            'unique_visitors' => 'integer',
            'total_pages_viewed' => 'integer',
            'daily_breakdown' => 'array',
            'archived_at' => 'datetime',
        ];
    }

    public function scopeOverlapping(Builder $query, mixed $from, mixed $to): Builder
    {
        return $query
            ->whereDate('period_start', '<=', $to)
            ->whereDate('period_end', '>=', $from);
    }
}
