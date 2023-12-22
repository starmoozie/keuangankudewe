<?php

namespace App\Services\Cache;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Spatie\ResponseCache\CacheProfiles\CacheAllSuccessfulGetRequests as BaseCacheProfile;

class CacheAllSuccessfulGetRequests extends BaseCacheProfile
{
    public function useCacheNameSuffix(Request $request): string
    {
        return starmoozie_auth()->check()
            ? (string) starmoozie_user()->id
            : '';
    }
}
