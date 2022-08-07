<?php

namespace HuubVerbeek\ConsistentHashing\Implementations;

use HuubVerbeek\ConsistentHashing\Contracts\SetterContract;

class DefaultSetter extends StoreImplementation implements SetterContract
{
    public function __invoke(string $key, mixed $value): void
    {
        $this->node->values[$key] = $value;
    }
}
