<?php

namespace Cesargb\Update\Tests;

use Cesargb\Update\UpdateServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            UpdateServiceProvider::class,
        ];
    }
}
