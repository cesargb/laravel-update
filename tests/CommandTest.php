<?php

namespace Cesargb\Update\Tests;

use Illuminate\Support\Facades\Artisan;

class CommandTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('update.require-dev', true);
    }

    /** @test */
    public function it_can_run_the_update_check_command()
    {
        $resultCode = Artisan::call('update:check');

        $this->assertEquals(0, $resultCode);
    }

    /** @test */
    public function it_can_run_the_update_packages_command()
    {
        $ctime_start = filectime(__DIR__.'/../vendor');

        sleep(1);

        $resultCode = Artisan::call('update:packages');

        $this->assertEquals(0, $resultCode);

        $ctime_end = filectime(__DIR__.'/../vendor');

        $this->assertNotEquals($ctime_start, $ctime_end);
    }
}
