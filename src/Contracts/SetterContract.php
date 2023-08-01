<?php

namespace HuubVerbeek\ConsistentHashing\Contracts;

interface SetterContract
{
    /**
     * @return mixed
     */
    public function set(string $key, mixed $value);

    /**
     * @return mixed
     */
    public function forget(string $key);
}
