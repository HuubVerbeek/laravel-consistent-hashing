<?php

namespace HuubVerbeek\ConsistentHashing\Contracts;

interface GetterContract
{
    public function get(string $key): mixed;

    public function all(): array;
}
