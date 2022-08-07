<?php

namespace HuubVerbeek\ConsistentHashing\Contracts;

interface ForwarderContract
{
    public function __invoke(string $key);
}
