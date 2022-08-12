<?php

namespace HuubVerbeek\ConsistentHashing;

use HuubVerbeek\ConsistentHashing\Exceptions\NodeCollectionException;
use HuubVerbeek\ConsistentHashing\Rules\NodeCollectionRule;

class StorageNodeCollection extends NodeCollection
{
    /**
     * @param  array  $nodes
     *
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

    /**
     * @return bool
     */
    public function wantsRekey(): bool
    {
        return true;
    }
}
