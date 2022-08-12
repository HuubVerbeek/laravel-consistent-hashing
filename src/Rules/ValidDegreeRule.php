<?php

namespace HuubVerbeek\ConsistentHashing\Rules;

use HuubVerbeek\ConsistentHashing\Contracts\RuleContract;

class ValidDegreeRule implements RuleContract
{
    /**
     * @param  int  $degree
     */
    public function __construct(private readonly int $degree)
    {
        //
    }

    /**
     * @return bool
     */
    public function passes(): bool
    {
        return ($this->degree >= 0) && ($this->degree <= 359);
    }
}
