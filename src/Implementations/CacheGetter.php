<?php

namespace HuubVerbeek\ConsistentHashing\Implementations;

use HuubVerbeek\ConsistentHashing\Contracts\GetterContract;
use HuubVerbeek\ConsistentHashing\Exceptions\InvalidCacheConfigurationException;
use HuubVerbeek\ConsistentHashing\Rules\ValidCacheConfigurationRule;
use HuubVerbeek\ConsistentHashing\Traits\Validator;
use Illuminate\Support\Facades\Cache;

class CacheGetter extends StoreImplementation implements GetterContract
{
    use Validator;

    public function __invoke(string $key): mixed
    {
        $this->validate(
            new ValidCacheConfigurationRule($this->node),
            new InvalidCacheConfigurationException()
        );

        return Cache::store($this->node->identifier)->get($key);
    }
}
