<?php

namespace HuubVerbeek\ConsistentHashing\Tests;

use HuubVerbeek\ConsistentHashing\ConsistentHashingCacheServiceProvider;

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
            ConsistentHashingCacheServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
