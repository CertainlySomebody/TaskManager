<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Task\Strategy;

use App\Application\Task\Strategy\DefaultTaskStatusTransitionStrategy;
use App\Domain\Task\ValueObject\TaskStatus;
use PHPUnit\Framework\TestCase;

class DefaultTaskStatusTransitionStrategyTest extends TestCase
{
    private DefaultTaskStatusTransitionStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new DefaultTaskStatusTransitionStrategy();
    }

    public function testCanTransitionFromTodoToInProgress(): void
    {
        $this->assertTrue($this->strategy->canTransition(TaskStatus::TODO, TaskStatus::IN_PROGRESS));
    }

    public function testCannotTransitionFromTodoToDone(): void
    {
        $this->assertFalse($this->strategy->canTransition(TaskStatus::TODO, TaskStatus::DONE));
    }

    public function testCanTransitionFromInProgressToDone(): void
    {
        $this->assertTrue($this->strategy->canTransition(TaskStatus::IN_PROGRESS, TaskStatus::DONE));
    }

    public function testCannotTransitionFromDone(): void
    {
        $this->assertFalse($this->strategy->canTransition(TaskStatus::DONE, TaskStatus::TODO));
    }
}
