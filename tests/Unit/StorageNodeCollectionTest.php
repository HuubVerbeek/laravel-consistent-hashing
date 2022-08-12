<?php

namespace HuubVerbeek\ConsistentHashing\Tests\Unit;

use HuubVerbeek\ConsistentHashing\Exceptions\NodeCollectionException;
use HuubVerbeek\ConsistentHashing\StorageNode;
use HuubVerbeek\ConsistentHashing\StorageNodeCollection;
use HuubVerbeek\ConsistentHashing\Tests\TestCase;

class StorageNodeCollectionTest extends TestCase
{
    public function test_node_collection_construction_fails_if_the_items_contain_a_non_node()
    {
        foreach (['string', 1, [1 => 12]] as $nonNode) {
            $this->executeNodeCollectionConstructionAsserts($nonNode);
        }
    }

    public function executeNodeCollectionConstructionAsserts(mixed $nonNode)
    {
        $this->expectException(NodeCollectionException::class);

        new StorageNodeCollection([
            new StorageNode(1, 'A'),
            $nonNode,
        ]);
    }

    public function test_can_get_to_next_node()
    {
        $nodeCollection = new StorageNodeCollection([
            new StorageNode(10, 'node_1'),
            new StorageNode(250, 'node_2'),
        ]);

        $node = $nodeCollection->next(5);

        $this->assertEquals('node_1', $node->identifier);

        $node = $nodeCollection->next(60);

        $this->assertEquals('node_2', $node->identifier);

        $node = $nodeCollection->next(260);

        $this->assertEquals('node_1', $node->identifier);
    }

    public function test_can_get_to_previous_node()
    {
        $nodeCollection = new StorageNodeCollection([
            new StorageNode(10, 'node_1'),
            new StorageNode(100, 'node_2'),
            new StorageNode(150, 'node_3'),
            new StorageNode(200, 'node_4'),
            new StorageNode(250, 'node_5'),
        ]);

        $node = $nodeCollection->previous(5);

        $this->assertEquals('node_5', $node->identifier);

        $node = $nodeCollection->previous(60);

        $this->assertEquals('node_1', $node->identifier);

        $node = $nodeCollection->previous(260);

        $this->assertEquals('node_5', $node->identifier);
    }
}
