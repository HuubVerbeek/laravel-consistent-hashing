<?php

namespace HuubVerbeek\ConsistentHashing\Tests\Unit;

use HuubVerbeek\ConsistentHashing\Exceptions\NodeCollectionException;
use HuubVerbeek\ConsistentHashing\Node;
use HuubVerbeek\ConsistentHashing\NodeCollection;
use HuubVerbeek\ConsistentHashing\Tests\TestCase;

class NodeCollectionTest extends TestCase
{
    public function test_node_collection_construction_fails_if_the_items_contain_a_non_node()
    {
        foreach (['string', 1, [1 => 12]] as $nonNode) {
            $this->executeNodeCollectionConstructionAsserts($nonNode);
        }

        // Can construct in the following cases.

        new NodeCollection();

        new NodeCollection([new Node(1, 'A')]);

        $this->assertTrue(true);
    }

    public function executeNodeCollectionConstructionAsserts(mixed $nonNode)
    {
        $this->expectException(NodeCollectionException::class);

        new NodeCollection([
            new Node(1, 'A'),
            $nonNode,
        ]);
    }
}
