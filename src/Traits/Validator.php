<?php

namespace HuubVerbeek\ConsistentHashing\Traits;

use HuubVerbeek\ConsistentHashing\Contracts\RuleContract;

trait Validator
{
    public function validate(RuleContract $rule, \Throwable $throwable): void
    {
        if (! $rule->passes()) {
            throw $throwable;
        }
    }
}
