<?php

namespace HuubVerbeek\ConsistentHashing\Contracts;

interface GetterContract
{
    public function __invoke(string $key): mixed;
}
