<?php

namespace HuubVerbeek\ConsistentHashing\Rules;

use HuubVerbeek\ConsistentHashing\Contracts\RuleContract;
use HuubVerbeek\ConsistentHashing\Node;

class NodeCollectionRule implements RuleContract
{
    public function __construct(private readonly array $nodes)
    {
        //
    }

    public function passes(): bool
    {
        return count(array_filter($this->nodes, fn ($node) => ! $node instanceof Node)) === 0;
    }
}
