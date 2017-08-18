<?php

namespace Cesargb\Update\Tests;

use Illuminate\Support\Facades\Artisan;

class ScheduleTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('update.require-dev', true);
        $this->app['config']->set('update.scheduler.check.cron', '* * * * * *');
        $this->app['config']->set('update.scheduler.update.cron', '* * * * * *');
        $this->app['config']->set('update.notifications.mail.to', '');
    }

    /** @test */
    public function it_can_run_the_update_check_schedule()
    {
        $this->app['config']->set('update.scheduler.check.enable', true);
        $this->app['config']->set('update.scheduler.update.enable', false);

        $ctime_start = filectime(__DIR__ . '/../vendor');

        sleep(1);

        $resultCode = Artisan::call('schedule:run');

        $this->assertEquals(0, $resultCode);

        $ctime_end = filectime(__DIR__ . '/../vendor');

        $this->assertNotEquals($ctime_start, $ctime_end);
    }

    /** @test */
    public function it_can_run_the_update_packages_command()
    {
        $this->app['config']->set('update.scheduler.check.enable', false);
        $this->app['config']->set('update.scheduler.update.enable', true);

        $ctime_start = filectime(__DIR__ . '/../vendor');

        sleep(1);

        $resultCode = Artisan::call('update:packages');

        $this->assertEquals(0, $resultCode);

        $ctime_end = filectime(__DIR__ . '/../vendor');

        $this->assertNotEquals($ctime_start, $ctime_end);
    }
}
