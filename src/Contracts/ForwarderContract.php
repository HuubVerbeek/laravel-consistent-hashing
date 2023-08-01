<?php

namespace HuubVerbeek\ConsistentHashing\Contracts;

interface ForwarderContract
{
    /**
     * @return mixed
     */
    public function handle($request); // Return type intentionally not defined.
}
