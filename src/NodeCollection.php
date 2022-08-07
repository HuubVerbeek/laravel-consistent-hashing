<?php

namespace HuubVerbeek\ConsistentHashing;

use HuubVerbeek\ConsistentHashing\Exceptions\NodeCollectionException;
use HuubVerbeek\ConsistentHashing\Rules\NodeCollectionRule;
use HuubVerbeek\ConsistentHashing\Traits\Validator;
use Illuminate\Support\Collection;

class NodeCollection extends Collection
{
    use Validator;

    public function __construct($nodes = [])
    {
        $this->validate(
            new NodeCollectionRule($nodes),
            new NodeCollectionException()
        );

        parent::__construct($nodes);
    }

    public function findByIdentifier(string $identifier): ?Node
    {
        return $this->filter(fn ($node) => $node->identifier === $identifier)->first();
    }
}
