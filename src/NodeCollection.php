<?php

namespace HuubVerbeek\ConsistentHashing;

use HuubVerbeek\ConsistentHashing\Traits\HasEvaluationStrategies;
use HuubVerbeek\ConsistentHashing\Traits\Validator;
use Illuminate\Support\Collection;

abstract class NodeCollection extends Collection
{
    use Validator,
        HasEvaluationStrategies;

    public function __construct(array $nodes)
    {
        parent::__construct($nodes);
    }

    public function findByIdentifier(string $identifier): ?AbstractNode
    {
        return $this->firstWhere(fn ($node) => $node->identifier === $identifier);
    }

    public function remove(string $identifier): bool
    {
        $this->forget(key($this->filter(fn ($node) => $node->identifier === $identifier)->items));

        return true;
    }

    public function next(int $degree): ?AbstractNode
    {
        $identifier = $this->evaluate($degree, $this->nextStrategy());

        return $this->findByIdentifier($identifier);
    }

    public function previous(int $degree): ?AbstractNode
    {
        $identifier = $this->evaluate($degree, $this->previousStrategy());

        return $this->findByIdentifier($identifier);
    }

    abstract public function wantsRekey(): bool;
}
