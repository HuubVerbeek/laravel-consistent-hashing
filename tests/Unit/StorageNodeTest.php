<?php

namespace HuubVerbeek\ConsistentHashing\Tests\Unit;

use HuubVerbeek\ConsistentHashing\Exceptions\InvalidDegreeException;
use HuubVerbeek\ConsistentHashing\Implementations\CacheGetter;
use HuubVerbeek\ConsistentHashing\Implementations\CacheSetter;
use HuubVerbeek\ConsistentHashing\Implementations\DefaultGetter;
use HuubVerbeek\ConsistentHashing\Implementations\DefaultSetter;
use HuubVerbeek\ConsistentHashing\StorageNode;
use HuubVerbeek\ConsistentHashing\Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class StorageNodeTest extends TestCase
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

    public function test_default_setter_is_set_when_not_passed_as_argument_during_construction()
    {
        $storageNode = invade(new StorageNode(rand(0, 359), Str::random(5)));

        $this->assertInstanceOf(DefaultSetter::class, $storageNode->setter);
    }

    public function test_default_getter_is_set_when_not_passed_as_argument_during_construction()
    {
        $storageNode = invade(new StorageNode(rand(0, 359), Str::random(5)));

        $this->assertInstanceOf(DefaultGetter::class, $storageNode->getter);
    }

    public function test_custom_setter_is_set_when_passed_as_argument_during_construction()
    {
        $storageNode = invade(new StorageNode(rand(0, 359), 'file', CacheSetter::class));

        $this->assertInstanceOf(CacheSetter::class, $storageNode->setter);
    }

    public function test_custom_getter_is_set_when_passed_as_argument_during_construction()
    {
        $storageNode = invade(new StorageNode(rand(0, 359), 'file', null, CacheGetter::class));

        $this->assertInstanceOf(CacheGetter::class, $storageNode->getter);
    }

    public function test_value_can_be_set()
    {
        foreach ([DefaultSetter::class, CacheSetter::class] as $setter) {
            $storageNode = new StorageNode(rand(0, 359), 'file', $setter);

            $storageNode->set(['key', 'value', 123, 123]);

            $setter === DefaultSetter::class
                ? $this->assertEquals('value', $storageNode->values['key'])
                : $this->assertEquals('value', Cache::store('file')->get('key'));
        }
    }

    public function test_value_can_be_retrieved()
    {
        foreach ([DefaultGetter::class, CacheGetter::class] as $getter) {
            $storageNode = new StorageNode(rand(0, 359), 'file', null, $getter);

            $getter === DefaultGetter::class
                ? $storageNode->values['key'] = 'value'
                : Cache::store('file')->put('key', 'value');

            $this->assertEquals('value', $storageNode->get(['key']));
        }
    }
}
