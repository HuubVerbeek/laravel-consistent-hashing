<?php

namespace HuubVerbeek\ConsistentHashing\Implementations;

use HuubVerbeek\ConsistentHashing\Contracts\GetterContract;

class DefaultGetter extends StoreImplementation implements GetterContract
{
    public function __invoke(string $key): mixed
    {
        return $this->node->values[$key];
    }
}
