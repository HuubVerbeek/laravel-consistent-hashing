<?php

namespace HuubVerbeek\ConsistentHashing\Rules;

use HuubVerbeek\ConsistentHashing\Contracts\RuleContract;
use HuubVerbeek\ConsistentHashing\NodeCollection;

class NodesAreNotEmptyRule implements RuleContract
{
    /**
     * @param  NodeCollection  $nodes
     */
    public function __construct(private readonly NodeCollection $nodes)
    {
        //
    }

    /**
     * @return bool
     */
    public function passes(): bool
    {
        return ! $this->nodes->isEmpty();
    }
}
