<?php

namespace HuubVerbeek\ConsistentHashing\Implementations;

use HuubVerbeek\ConsistentHashing\Contracts\SetterContract;
use HuubVerbeek\ConsistentHashing\Exceptions\InvalidCacheConfigurationException;
use HuubVerbeek\ConsistentHashing\Rules\ValidCacheConfigurationRule;
use HuubVerbeek\ConsistentHashing\Traits\Validator;
use Illuminate\Support\Facades\Cache;

class CacheSetter extends StoreImplementation implements SetterContract
{
    use Validator;

    public function __invoke(string $key, mixed $value): void
    {
        $this->validate(
            new ValidCacheConfigurationRule($this->node),
            new InvalidCacheConfigurationException()
        );

        Cache::store($this->node->identifier)->put($key, $value);
    }
}
