<?php

namespace HuubVerbeek\ConsistentHashing\Implementations;

use HuubVerbeek\ConsistentHashing\StorageNode;

abstract class StoreImplementation
{
    /**
     * @param  StorageNode  $node
     */
    public function __construct(protected StorageNode $node)
    {
        //
    }
}
