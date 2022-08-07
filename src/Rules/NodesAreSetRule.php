<?php

namespace HuubVerbeek\ConsistentHashing\Rules;

use HuubVerbeek\ConsistentHashing\Contracts\RuleContract;
use HuubVerbeek\ConsistentHashing\NodeCollection;

class NodesAreSetRule implements RuleContract
{
    public function __construct(private readonly NodeCollection $nodes)
    {
        //
    }

    public function passes(): bool
    {
        return ! $this->nodes->isEmpty();
    }
}
