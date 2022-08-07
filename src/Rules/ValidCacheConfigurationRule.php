<?php

namespace HuubVerbeek\ConsistentHashing\Rules;

use HuubVerbeek\ConsistentHashing\Contracts\RuleContract;
use HuubVerbeek\ConsistentHashing\Node;

class ValidCacheConfigurationRule implements RuleContract
{
    public function __construct(private readonly Node $node)
    {
        //
    }

    public function passes(): bool
    {
        return array_key_exists($this->node->identifier, config('cache.stores'));
    }
}
