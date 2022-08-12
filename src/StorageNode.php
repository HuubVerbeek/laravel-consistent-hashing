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
    /**
     * @var SetterContract
     */
    private SetterContract $setter;

    /**
     * @var GetterContract
     */
    private GetterContract $getter;

    /**
     * @var array
     */
    public array $values;

    /**
     * @param  int  $degree
     * @param  string  $identifier
     * @param  string|null  $setter
     * @param  string|null  $getter
     */
    public function __construct(int $degree, string $identifier, ?string $setter = null, ?string $getter = null)
    {
        parent::__construct($degree, $identifier);

        $this->setter = empty($setter)
            ? new DefaultSetter($this)
            : new $setter($this);

        $this->getter = empty($getter)
            ? new DefaultGetter($this)
            : new $getter($this);

        $this->values = [];
    }

    /**
     * @param  array  $args
     * @return void
     */
    public function set(array $args = []): void
    {
        $this->setter->set(...$args);
    }

    /**
     * @param  array  $args
     * @return void
     */
    public function forget(array $args = []): void
    {
        $this->setter->forget(...$args);
    }

    /**
     * @param  array  $args
     * @return mixed
     */
    public function get(array $args = []): mixed
    {
        return $this->getter->get(...$args);
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return collect($this->getter->all());
    }

    /**
     * @param  StorageNode  $target
     * @return Closure
     */
    public function moveItemTo(StorageNode $target): Closure
    {
        return function ($value, $key) use ($target) {
            $target->set([$key, $value]);
            $this->forget([$key]);
        };
    }
}
