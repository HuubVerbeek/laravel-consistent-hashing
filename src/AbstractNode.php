<?php

namespace HuubVerbeek\ConsistentHashing;

use HuubVerbeek\ConsistentHashing\Exceptions\InvalidDegreeException;
use HuubVerbeek\ConsistentHashing\Rules\ValidDegreeRule;
use HuubVerbeek\ConsistentHashing\Traits\Validator;

abstract class AbstractNode
{
    use Validator;

    /**
     * @var float|int
     */
    public float $degree;

    /**
     * @throws \Throwable
     */
    public function __construct(
        int $degree,
        public string $identifier,
    ) {
        $this->validate(
            new ValidDegreeRule($degree),
            new InvalidDegreeException(),
        );

        $this->degree = $degree;
    }
}
