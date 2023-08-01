<?php

namespace HuubVerbeek\ConsistentHashing\Rules;

use HuubVerbeek\ConsistentHashing\Contracts\RuleContract;

class ReservedCacheKeyRule implements RuleContract
{
    public function __construct(private readonly string $key, private readonly array $reserved)
    {
        //
    }

    public function passes(): bool
    {
        return ! in_array($this->key, $this->reserved);
    }
}
