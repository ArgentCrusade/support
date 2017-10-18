<?php

namespace ArgentCrusade\Support;

use Illuminate\Cache\TaggableStore;
use Illuminate\Support\Facades\Cache;

class AppRevision
{
    private const REVISION_FILE = 'revision.hash';

    /**
     * Get the application revision hash.
     *
     * @return string
     */
    public static function get()
    {
        return static::cache()->remember('app.revision', 60, function () {
            if (!file_exists(storage_path(static::REVISION_FILE))) {
                return 'develop';
            }

            return trim(file_get_contents(storage_path(static::REVISION_FILE)), "\n");
        });
    }

    /**
     * Get cache store.
     *
     * @return \Illuminate\Cache\TaggedCache|\Illuminate\Contracts\Cache\Store
     */
    protected static function cache()
    {
        $store = Cache::getStore();

        return ($store instanceof TaggableStore ? $store->tags(config('support.deploys.cache_tags')) : $store);
    }
}
