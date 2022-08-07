<?php

namespace HuubVerbeek\ConsistentHashing\Tests\Unit;

use HuubVerbeek\ConsistentHashing\Exceptions\InvalidDegreeException;
use HuubVerbeek\ConsistentHashing\Implementations\CacheGetter;
use HuubVerbeek\ConsistentHashing\Implementations\CacheSetter;
use HuubVerbeek\ConsistentHashing\StorageNode;
use HuubVerbeek\ConsistentHashing\Tests\TestCase;

class NodeTest extends TestCase
{
    public function test_node_construction_fails_if_not_a_valid_degree()
    {
        $this->expectException(InvalidDegreeException::class);

        new StorageNode(400, 'A');

        new StorageNode(-50, 'B');
    }

    public function test_node_construction_succeeds_if_valid_degree()
    {
        new StorageNode(50, 'B');

        new StorageNode(200, 'C');

        $this->assertTrue(true);
    }

    public function test_node_closures()
    {
        $node = new StorageNode(
            200,
            'file',
            CacheSetter::class,
            CacheGetter::class
        );

        $node->set(['key1', 123]);

        $node->get(['key1']);

        $this->assertEquals($node->get(['key1']), 123);
    }
}
