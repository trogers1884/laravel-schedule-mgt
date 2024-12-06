<?php

namespace Trogers1884\LaravelScheduleMgt\Tests\Unit;

use Trogers1884\LaravelScheduleMgt\Tests\TestCase;
use Trogers1884\LaravelScheduleMgt\ScheduleManager;
use Trogers1884\LaravelScheduleMgt\Support\ScheduleStorage;

class ScheduleManagerTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $manager = new ScheduleManager(new ScheduleStorage());
        $this->assertInstanceOf(ScheduleManager::class, $manager);
    }

    /** @test */
    public function it_can_add_a_task()
    {
        $manager = new ScheduleManager(new ScheduleStorage());

        $id = $manager->addTask(
            'cache:clear',
            'daily'
        );

        $this->assertIsInt($id);
        $this->assertGreaterThan(0, $id);
    }
}