<?php

namespace HuubVerbeek\ConsistentHashing;

use Closure;
use HuubVerbeek\ConsistentHashing\Contracts\GetterContract;
use HuubVerbeek\ConsistentHashing\Contracts\SetterContract;
use HuubVerbeek\ConsistentHashing\Implementations\DefaultGetter;
use HuubVerbeek\ConsistentHashing\Implementations\DefaultSetter;
use Illuminate\Support\Collection;

class StorageNode extends AbstractNode
{
    private SetterContract $setter;

    private GetterContract $getter;

    public array $values;

    public function __construct(int $degree, string $identifier, string $setter = null, string $getter = null)
    {
        parent::__construct($degree, $identifier);

        $this->setter = is_null($setter)
            ? new DefaultSetter($this)
            : new $setter($this);

        $this->getter = is_null($getter)
            ? new DefaultGetter($this)
            : new $getter($this);

        $this->values = [];
    }

    public function set(array $args = []): void
    {
        $this->setter->set(...$args);
    }

    public function forget(array $args = []): void
    {
        $this->setter->forget(...$args);
    }

    public function get(array $args = []): mixed
    {
        return $this->getter->get(...$args);
    }

    public function all(): Collection
    {
        return collect($this->getter->all());
    }

    public function moveItemTo(StorageNode $target): Closure
    {
        return function ($value, $key) use ($target) {
            $target->set([$key, $value]);
            $this->forget([$key]);
        };
    }
}
