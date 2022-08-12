<?php

namespace HuubVerbeek\ConsistentHashing\Implementations;

use HuubVerbeek\ConsistentHashing\Contracts\SetterContract;

class DefaultSetter extends StoreImplementation implements SetterContract
{
    /**
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $this->node->values[$key] = $value;
    }

    /**
     * @param  string  $key
     * @return void
     */
    public function forget(string $key): void
    {
        unset($this->node->values[$key]);
    }
}
