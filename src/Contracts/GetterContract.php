<?php

namespace HuubVerbeek\ConsistentHashing\Contracts;

interface GetterContract
{
    /**
     * @param  string  $key
     * @return mixed
     */
    public function get(string $key): mixed;

    /**
     * @return array
     */
    public function all(): array;
}
