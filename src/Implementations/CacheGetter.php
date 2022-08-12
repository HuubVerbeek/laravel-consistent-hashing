<?php

namespace HuubVerbeek\ConsistentHashing\Implementations;

use HuubVerbeek\ConsistentHashing\Contracts\GetterContract;
use HuubVerbeek\ConsistentHashing\Exceptions\ReservedCacheKeyException;
use HuubVerbeek\ConsistentHashing\Rules\ReservedCacheKeyRule;
use HuubVerbeek\ConsistentHashing\Traits\Validator;
use Illuminate\Support\Facades\Cache;

class CacheGetter extends StoreImplementation implements GetterContract
{
    use Validator;

    private const RESERVED_CACHE_KEYS = ['currently_cached_keys'];

    /**
     * @param  string  $key
     * @return mixed
     *
     * @throws \Throwable
     */
    public function get(string $key): mixed
    {
        $this->validate(
            new ReservedCacheKeyRule($key, self::RESERVED_CACHE_KEYS),
            new ReservedCacheKeyException()
        );

        return Cache::store($this->node->identifier)->get($key);
    }

    /**
     * @return array
     */
    public function all(): array
    {
        if (! Cache::has(self::CACHE_KEY_ALL)) {
            return [];
        }

        return Cache::get(self::CACHE_KEY_ALL);
    }
}