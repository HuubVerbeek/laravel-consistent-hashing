<?php

namespace HuubVerbeek\ConsistentHashing\Tests\Unit;

use HuubVerbeek\ConsistentHashing\Rules\NodeCollectionRule;
use HuubVerbeek\ConsistentHashing\Rules\NodesAreNotEmptyRule;
use HuubVerbeek\ConsistentHashing\Rules\ReservedCacheKeyRule;
use HuubVerbeek\ConsistentHashing\Rules\ValidDegreeRule;
use HuubVerbeek\ConsistentHashing\StorageNode;
use HuubVerbeek\ConsistentHashing\StorageNodeCollection;
use HuubVerbeek\ConsistentHashing\Tests\TestCase;
use Illuminate\Support\Str;

class RulesTest extends TestCase
{
    public function test_node_collection_rule_with_valid_data()
    {
        $node = new StorageNode(rand(0, 359), Str::random(5));

        $nodes = [$node, $node];

        $rule = new NodeCollectionRule($nodes);

        $this->assertTrue($rule->passes());
    }

    public function test_node_collection_rule_with_empty_array()
    {
        $nodes = [];

        $rule = new NodeCollectionRule($nodes);

        $this->assertTrue($rule->passes());
    }

    public function test_node_collection_rule_with_array_of_integers()
    {
        $nodes = [1, 2, 3, 4];

        $rule = new NodeCollectionRule($nodes);

        $this->assertFalse($rule->passes());
    }

    public function test_node_collection_rule_with_array_of_strings()
    {
        $nodes = ['a', 'b', 'b'];

        $rule = new NodeCollectionRule($nodes);

        $this->assertFalse($rule->passes());
    }

    public function test_nodes_are_set_rule_when_nodes_not_empty()
    {
        $rule = new NodesAreNotEmptyRule(new StorageNodeCollection([new StorageNode(1, 'A')]));

        $this->assertTrue($rule->passes());
    }

    public function test_valid_degree_rule_when_value_in_valid_range()
    {
        foreach (range(0, 359) as $degree) {
            $this->assertTrue((new ValidDegreeRule($degree))->passes());
        }
    }

    public function test_valid_degree_rule_when_value_in_invalid_range()
    {
        foreach (range(-100, -1) as $degree) {
            $this->assertFalse((new ValidDegreeRule($degree))->passes());
        }

        foreach (range(360, 460) as $degree) {
            $this->assertFalse((new ValidDegreeRule($degree))->passes());
        }
    }

    public function test_reserved_cache_key_rule_when_key_not_reserved()
    {
        $rule = new ReservedCacheKeyRule('key', ['reserved']);

        $this->assertTrue($rule->passes());
    }

    public function test_reserved_cache_key_rule_when_key_is_reserved()
    {
        $rule = new ReservedCacheKeyRule('reserved', ['reserved']);

        $this->assertFalse($rule->passes());
    }
}
