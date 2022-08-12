<?php

namespace HuubVerbeek\ConsistentHashing\Tests\Unit;

use HuubVerbeek\ConsistentHashing\ConsistentHashingService;
use HuubVerbeek\ConsistentHashing\StorageNode;
use HuubVerbeek\ConsistentHashing\StorageNodeCollection;
use HuubVerbeek\ConsistentHashing\Tests\TestCase;
use Illuminate\Support\Str;

/**
 * @property ConsistentHashingService $service
 */
class ConsistentHashingServiceTest extends TestCase
{
    private ConsistentHashingService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->nodesCollection = new StorageNodeCollection([
            new StorageNode(0, 0),
            new StorageNode(90, 1),
            new StorageNode(180, 2),
            new StorageNode(270, 3),
        ]);

        $this->service = (new ConsistentHashingService($this->nodesCollection));
    }

    public function test_get_valid_degree_from_string()
    {
        foreach (range(1, 1000) as $i) {
            $degree = $this->service->getDegree(Str::random(10));

            $this->assertGreaterThanOrEqual(0, $degree);

            $this->assertLessThanOrEqual(360, $degree);
        }
    }

    public function test_a_key_will_get_resolved_to_the_next_node()
    {
        $degree = $this->service->getDegree('test');

        $expected = $this->service->nextNode($degree);

        $node = $this->service->resolve('test');

        $this->assertEquals($expected, $node);
    }

    public function test_selection_of_the_next_node()
    {
        $node = $this->service->nextNode(0);

        $this->assertEquals(0, $node->identifier);

        foreach (range(1, 90) as $i) {
            $node = $this->service->nextNode($i);
            $this->assertEquals(1, $node->identifier);
        }

        foreach (range(91, 180) as $i) {
            $node = $this->service->nextNode(($i));
            $this->assertEquals(2, $node->identifier);
        }

        foreach (range(181, 270) as $i) {
            $node = $this->service->nextNode($i);
            $this->assertEquals(3, $node->identifier);
        }

        foreach (range(271, 360) as $i) {
            $node = $this->service->nextNode($i);
            $this->assertEquals(0, $node->identifier);
        }
    }

    public function test_selection_of_the_previous_node()
    {
        $node = $this->service->previousNode(0);

        $this->assertEquals(3, $node->identifier);

        foreach (range(1, 90) as $i) {
            $node = $this->service->previousNode($i);
            $this->assertEquals(0, $node->identifier);
        }

        foreach (range(91, 180) as $i) {
            $node = $this->service->previousNode(($i));
            $this->assertEquals(1, $node->identifier);
        }

        foreach (range(181, 270) as $i) {
            $node = $this->service->previousNode($i);
            $this->assertEquals(2, $node->identifier);
        }

        foreach (range(271, 360) as $i) {
            $node = $this->service->previousNode($i);
            $this->assertEquals(3, $node->identifier);
        }
    }

    public function test_remove_node()
    {
        foreach ($this->service->nodeCollection as $key => $node) {
            foreach (range(1, 10) as $index) {
                $node->set(["{$node->identifier}_{$index}", "{$node->identifier}_{$index}"]);
            }
        }

        $values = $this->service->nodeCollection->findByIdentifier(1)->all();

        $this->service->removeNode(1);

        $next = $this->service->nodeCollection->findByIdentifier(2);

        $this->assertCount(10, $next->all()->intersect(collect($values)));
    }

    public function test_add_node()
    {
        foreach (range(0, 100) as $i) {
            $key = Str::random(5);
            $node = $this->service->resolve($key);

            $node->set([$key, $node->identifier]);
        }

        $ninetyDegrees = $this->service->nodeCollection->findByIdentifier(1);

        $underSixtyOne =
            $ninetyDegrees->all()->filter(
                fn ($item, $key) => $this->service->getDegree($key) < 61
            );

        $this->service->addNode(new StorageNode(60, 0.5));

        $added = $this->service->nodeCollection->findByIdentifier(0.5);

        $this->assertEquals($underSixtyOne, $added->all());
    }
}
