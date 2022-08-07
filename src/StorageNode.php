<?php

namespace HuubVerbeek\ConsistentHashing;

use HuubVerbeek\ConsistentHashing\Contracts\GetterContract;
use HuubVerbeek\ConsistentHashing\Contracts\SetterContract;
use HuubVerbeek\ConsistentHashing\Implementations\DefaultGetter;
use HuubVerbeek\ConsistentHashing\Implementations\DefaultSetter;

class StorageNode extends Node
{
    /**
     * @var SetterContract
     */
    protected SetterContract $setter;

    /**
     * @var GetterContract
     */
    private GetterContract $getter;

    /**
     * @var array
     */
    public array $values;

    public function __construct(float $degree, string $identifier, ?string $setter = null, ?string $getter = null)
    {
        parent::__construct($degree, $identifier);

        $this->__setSetter($setter);

        $this->__setGetter($getter);

        $this->values = [];
    }

    private function __setSetter(?string $setter): void
    {
        $this->setter = empty($setter)
            ? new DefaultSetter($this)
            : new $setter($this);
    }

    private function __setGetter(?string $getter): void
    {
        $this->getter = empty($getter)
            ? new DefaultGetter($this)
            : new $getter($this);
    }

    public function set(array $args = []): void
    {
        $callable = $this->setter;

        $callable(...$args);
    }

    public function get(array $args = []): mixed
    {
        $callable = $this->getter;

        return $callable(...$args);
    }
}
