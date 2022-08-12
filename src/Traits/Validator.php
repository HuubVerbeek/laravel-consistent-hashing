<?php

namespace HuubVerbeek\ConsistentHashing\Traits;

use HuubVerbeek\ConsistentHashing\Contracts\RuleContract;

trait Validator
{
    /**
     * @param  RuleContract  $rule
     * @param  \Throwable  $throwable
     * @return void
     *
     * @throws \Throwable
     */
    public function validate(RuleContract $rule, \Throwable $throwable): void
    {
        if (! $rule->passes()) {
            throw $throwable;
        }
    }
}
