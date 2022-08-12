<?php

namespace HuubVerbeek\ConsistentHashing\Contracts;

interface ForwarderContract
{
    /**
     * @param $request
     * @return mixed
     */
    public function handle($request); // Return type intentionally not defined.
}
