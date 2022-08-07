<?php

namespace HuubVerbeek\ConsistentHashing;

use HuubVerbeek\ConsistentHashing\Exceptions\NoNodesSetException;
use HuubVerbeek\ConsistentHashing\Rules\NodesAreSetRule;
use HuubVerbeek\ConsistentHashing\Traits\Validator;

/**
 * @property NodeCollection $nodes
 */
class ConsistentHashingService
{
    use Validator;

    public NodeCollection $nodes;

    public function __construct()
    {
        $this->nodes = new NodeCollection();
    }

    public function setNodes(NodeCollection $nodes): ConsistentHashingService
    {
        $this->nodes = $nodes;

        return $this;
    }

    public function resolveNode(string $key): Node
    {
        $degree = $this->getDegree($key);

        return $this->nextNode($degree);
    }

    public function getDegree(string $string): float
    {
        $hex = md5($string);

        $dec = hexdec($hex) / pow(10, 25);

        return $dec % 360;
    }

    public function nextNode(float $degree): Node
    {
        $this->validate(
            new NodesAreSetRule($this->nodes),
            new NoNodesSetException(),
        );

        $positiveValues = [];

        $results = $this->computeDistances($this->nodes, $degree, $positiveValues);

        if (count($positiveValues) === 0) {
            return $this->nodes->findByIdentifier(
                $this->getKeyFromMinValue($results)
            );
        }

        return $this->nodes->findByIdentifier(
            $this->getKeyFromMinValue($this->removeNegativeValues($results))
        );
    }

    public function computeDistances(NodeCollection $nodes, float $degree, &$positiveValues): array
    {
        $results = [];

        foreach ($nodes as $node) {
            $results[$node->identifier] = $node->degree - $degree;

            if ($results[$node->identifier] >= 0) {
                $positiveValues[] = $results[$node->identifier];
            }
        }

        return $results;
    }

    public function removeNegativeValues(array $results): array
    {
        return array_filter($results, fn ($result) => $result >= 0);
    }

    public function getKeyFromMinValue($results): string
    {
        asort($results);

        return key($results);
    }
}
