<?php

namespace HuubVerbeek\ConsistentHashing\Implementations;

use HuubVerbeek\ConsistentHashing\StorageNode;

abstract class StoreImplementation
{
    public function __construct(protected StorageNode $node)
    {
        //
    }
}
