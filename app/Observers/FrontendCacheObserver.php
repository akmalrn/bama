<?php

namespace App\Observers;

use App\Support\FrontendCache;
use Illuminate\Database\Eloquent\Model;

class FrontendCacheObserver
{
    /**
     * Handle the Model "saved" event (covers created and updated).
     */
    public function saved(Model $model): void
    {
        FrontendCache::invalidateForModel($model);
    }

    /**
     * Handle the Model "deleted" event.
     */
    public function deleted(Model $model): void
    {
        FrontendCache::invalidateForModel($model);
    }

    /**
     * Handle the Model "restored" event.
     */
    public function restored(Model $model): void
    {
        FrontendCache::invalidateForModel($model);
    }
}
