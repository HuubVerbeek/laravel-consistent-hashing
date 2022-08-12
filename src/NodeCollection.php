<?php

namespace HuubVerbeek\ConsistentHashing;

use HuubVerbeek\ConsistentHashing\Traits\HasEvaluationStrategies;
use HuubVerbeek\ConsistentHashing\Traits\Validator;
use Illuminate\Support\Collection;

abstract class NodeCollection extends Collection
{
    use Validator,
        HasEvaluationStrategies;

    /**
     * @param  array  $nodes
     */
    public function __construct(array $nodes)
    {
        parent::__construct($nodes);
    }

    /**
     * @param  string  $identifier
     * @return AbstractNode|null
     */
    public function findByIdentifier(string $identifier): ?AbstractNode
    {
        return $this->firstWhere(fn ($node) => $node->identifier === $identifier);
    }

    /**
     * @param  string  $identifier
     * @return bool
     */
    public function remove(string $identifier): bool
    {
        $this->forget(key($this->filter(fn ($node) => $node->identifier === $identifier)->items));

        return true;
    }

    /**
     * @param  int  $degree
     * @return AbstractNode|null
     */
    public function next(int $degree): ?AbstractNode
    {
        $identifier = $this->evaluate($degree, $this->nextStrategy());

        return $this->findByIdentifier($identifier);
    }

    /**
     * @param  int  $degree
     * @return AbstractNode|null
     */
    public function previous(int $degree): ?AbstractNode
    {
        $identifier = $this->evaluate($degree, $this->previousStrategy());

        return $this->findByIdentifier($identifier);
    }

    /**
     * @return bool
     */
    abstract public function wantsRekey(): bool;
}
