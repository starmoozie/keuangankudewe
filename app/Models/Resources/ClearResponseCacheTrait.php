<?php

namespace App\Models\Resources;

use Spatie\ResponseCache\Facades\ResponseCache;

trait ClearsResponseCache
{
    public static function bootClearsResponseCache()
    {
        Self::created(fn () => ResponseCache::clear());
        Self::updated(fn () => ResponseCache::clear());
        Self::deleted(fn () => ResponseCache::clear());
    }
}
