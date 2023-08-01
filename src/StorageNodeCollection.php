<?php

namespace HuubVerbeek\ConsistentHashing;

use HuubVerbeek\ConsistentHashing\Exceptions\NodeCollectionException;
use HuubVerbeek\ConsistentHashing\Rules\NodeCollectionRule;

class StorageNodeCollection extends NodeCollection
{
    /**
     * @throws \Throwable
     */
    public function __construct(array $nodes)
    {
        $this->validate(
            new NodeCollectionRule($nodes, StorageNode::class),
            new NodeCollectionException(StorageNode::class)
        );

        parent::__construct($nodes);
    }

    public function wantsRekey(): bool
    {
        return true;
    }
}
