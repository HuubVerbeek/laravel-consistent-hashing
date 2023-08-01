<?php

namespace HuubVerbeek\ConsistentHashing\Implementations;

use HuubVerbeek\ConsistentHashing\Contracts\SetterContract;

class DefaultSetter extends StoreImplementation implements SetterContract
{
    public function set(string $key, mixed $value): void
    {
        $this->node->values[$key] = $value;
    }

    public function forget(string $key): void
    {
        unset($this->node->values[$key]);
    }
}
