<?php

namespace HuubVerbeek\ConsistentHashing\Contracts;

interface SetterContract
{
    public function __invoke(string $key, mixed $value);
}
