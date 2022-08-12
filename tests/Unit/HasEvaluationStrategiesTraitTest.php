<?php

namespace HuubVerbeek\ConsistentHashing\Tests\Unit;

use HuubVerbeek\ConsistentHashing\StorageNode;
use HuubVerbeek\ConsistentHashing\StorageNodeCollection;
use HuubVerbeek\ConsistentHashing\Tests\TestCase;

class HasEvaluationStrategiesTraitTest extends TestCase
{
    public function test_computing_results_based_on_input_degree()
    {
        $this->executeComputingDistancesAsserts(90, collect([
            0 => -90,
            1 => 0,
            2 => 90,
            3 => 180,
        ]), false);

        $this->executeComputingDistancesAsserts(0, collect([
            0 => 0,
            1 => 90,
            2 => 180,
            3 => 270,
        ]), false);

        $this->executeComputingDistancesAsserts(360, collect([
            0 => -360,
            1 => -270,
            2 => -180,
            3 => -90,
        ]), true);
    }

    public function executeComputingDistancesAsserts(float $degree, $expectedResults, $onlyNegativeValues)
    {
        $nodeCollection = new StorageNodeCollection([
            new StorageNode(0, 0),
            new StorageNode(90, 1),
            new StorageNode(180, 2),
            new StorageNode(270, 3),
        ]);

        $results = $nodeCollection->computeDistances($nodeCollection, $degree);

        $this->assertEquals($expectedResults, $results);

        $this->assertEquals($onlyNegativeValues, $nodeCollection->hasOnlyNegativeValues($results));
    }
}
