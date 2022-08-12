<?php

namespace HuubVerbeek\ConsistentHashing\Contracts;

interface RuleContract
{
    /**
     * @return bool
     */
    public function passes(): bool;
}
