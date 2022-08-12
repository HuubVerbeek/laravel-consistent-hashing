<?php

namespace HuubVerbeek\ConsistentHashing\Implementations;

use HuubVerbeek\ConsistentHashing\Contracts\SetterContract;
use HuubVerbeek\ConsistentHashing\Exceptions\ReservedCacheKeyException;
use HuubVerbeek\ConsistentHashing\Rules\ReservedCacheKeyRule;
use HuubVerbeek\ConsistentHashing\Traits\Validator;
use Illuminate\Support\Facades\Cache;

class CacheSetter extends StoreImplementation implements SetterContract
{
    use Validator;

    private const RESERVED_CACHE_KEYS = ['all_cached_keys'];

    /**
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     *
     * @throws ReservedCacheKeyException
     * @throws \Throwable
     */
    public function set(string $key, mixed $value): void
    {
        $this->validate(
            new ReservedCacheKeyRule($key, self::RESERVED_CACHE_KEYS),
            new ReservedCacheKeyException()
        );

        Cache::store($this->node->identifier)->put($key, $value);

        $this->updateKeysList($key);
    }

    /**
     * @param  string  $key
     * @return void
     */
    public function forget(string $key): void
    {
        Cache::store($this->node->identifier)->forget($key);
    }

    /**
     * @param $key
     * @return void
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function updateKeysList($key): void
    {
        $keys = Cache::store($this->node->identifier)->get('all_cached_keys') ?? [];

        Cache::store($this->node->identifier)->put('all_cached_keys', array_merge($keys, [$key]));
    }
}
