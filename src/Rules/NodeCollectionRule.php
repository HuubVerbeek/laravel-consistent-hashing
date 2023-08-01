<?php

namespace HuubVerbeek\ConsistentHashing\Rules;

use HuubVerbeek\ConsistentHashing\AbstractNode;
use HuubVerbeek\ConsistentHashing\Contracts\RuleContract;

class NodeCollectionRule implements RuleContract
{
    public function __construct(private readonly array $nodes, public string $type = AbstractNode::class)
    {
        //
    }

    public function passes(): bool
    {
        return count(array_filter($this->nodes, fn ($node) => ! is_a($node, $this->type))) === 0;
    }
}
