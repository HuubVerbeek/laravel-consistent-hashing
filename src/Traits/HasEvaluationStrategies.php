<?php

namespace HuubVerbeek\ConsistentHashing\Traits;

use HuubVerbeek\ConsistentHashing\NodeCollection;
use Illuminate\Support\Collection;

trait HasEvaluationStrategies
{
    public function evaluate(float $degree, $strategy): string|int
    {
        $distancesToDegree = $this->computeDistances($this, $degree);

        return $strategy($distancesToDegree);
    }

    public function nextStrategy(): callable
    {
        return function ($distances) {
            if ($this->hasOnlyNegativeValues($distances)) {
                return $this->getKey($distances, 'ASC');
            }

            $distances = $distances->filter(fn ($distance) => $distance >= 0);

            return $this->getKey($distances, 'ASC');
        };
    }

    public function previousStrategy(): callable
    {
        return function ($distances) {
            if ($this->hasOnlyPositiveValues($distances)) {
                return $this->getKey($distances, 'DESC');
            }

            $distances = $distances->filter(fn ($distance) => $distance < 0);

            return $this->getKey($distances, 'DESC');
        };
    }

    public function computeDistances(NodeCollection $nodes, float $degree): Collection
    {
        $distances = [];

        foreach ($nodes as $node) {
            $distances[$node->identifier] = $node->degree - $degree;
        }

        return collect($distances);
    }

    public function getKey(Collection $distances, string $dir): string|int
    {
        $distances = $distances->toArray();

        $dir === 'ASC'
            ? asort($distances)
            : arsort($distances);

        return key($distances);
    }

    public function hasOnlyNegativeValues(Collection $distances): bool
    {
        return $distances->every(fn ($distance) => $distance < 0);
    }

    public function hasOnlyPositiveValues(Collection $distances): bool
    {
        return $distances->every(fn ($distance) => $distance >= 0);
    }
}
