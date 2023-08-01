<?php

namespace HuubVerbeek\ConsistentHashing;

use HuubVerbeek\ConsistentHashing\Contracts\ForwarderContract;

class ForwarderNode extends AbstractNode
{
    private ForwarderContract $forwarder;

    /**
     * @throws \Throwable
     */
    public function __construct(int $degree, string $identifier, string $forwarder)
    {
        parent::__construct($degree, $identifier);

        $this->forwarder = new $forwarder($this);
    }

    /**
     * @return mixed
     */
    public function handle(array $args) // Return type intentionally not defined.
    {
        $callable = $this->forwarder;

        return $callable->handle(...$args);
    }
}
