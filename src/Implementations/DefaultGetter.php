<?php

namespace HuubVerbeek\ConsistentHashing\Implementations;

use HuubVerbeek\ConsistentHashing\Contracts\GetterContract;

class DefaultGetter extends StoreImplementation implements GetterContract
{
    public function get(string $key): mixed
    {
        return $this->node->values[$key];
    }

    public function all(): array
    {
        return $this->node->values;
    }
}
