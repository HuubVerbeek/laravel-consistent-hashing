<?php

namespace HuubVerbeek\ConsistentHashing;

use HuubVerbeek\ConsistentHashing\Exceptions\InvalidDegreeException;
use HuubVerbeek\ConsistentHashing\Rules\ValidDegreeRule;
use HuubVerbeek\ConsistentHashing\Traits\Validator;

class Node
{
    use Validator;

    public float $degree;

    public function __construct(
        float $degree,
        public string $identifier,
    ) {
        $this->validate(
            new ValidDegreeRule($degree),
            new InvalidDegreeException(),
        );

        $this->degree = $degree;
    }
}
