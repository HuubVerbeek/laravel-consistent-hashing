<?php

namespace HuubVerbeek\ConsistentHashing\Tests;

use HuubVerbeek\ConsistentHashing\ConsistentHashingServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    protected function getPackageProviders($app)
    {
        return [
            ConsistentHashingServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
