<?php

namespace HuubVerbeek\ConsistentHashing\Implementations;

use HuubVerbeek\ConsistentHashing\Contracts\GetterContract;

class DefaultGetter extends StoreImplementation implements GetterContract
{
    /**
     * @param  string  $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        return $this->node->values[$key];
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->node->values;
    }
}
