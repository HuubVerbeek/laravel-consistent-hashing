<?php

namespace HuubVerbeek\ConsistentHashing\Contracts;

interface SetterContract
{
    /**
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    public function set(string $key, mixed $value);

    /**
     * @param  string  $key
     * @return mixed
     */
    public function forget(string $key);
}
