<?php

namespace HuubVerbeek\ConsistentHashing\Contracts;

interface RuleContract
{
    public function passes(): bool;
}
