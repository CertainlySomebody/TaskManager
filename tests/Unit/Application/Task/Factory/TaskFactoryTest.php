<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Task\Factory;

use App\Application\Task\Factory\TaskFactory;
use App\Domain\Task\ValueObject\TaskStatus;
use PHPUnit\Framework\TestCase;

class TaskFactoryTest extends TestCase
{
    private TaskFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new TaskFactory();
    }

    public function testCreateTaskWithDefaultStatus(): void
    {
        $task = $this->factory->create(
            'Test Task',
            'Test Description',
            'some-user-id'
        );

        $this->assertSame('Test Task', $task->getTitle());
        $this->assertSame('Test Description', $task->getDescription());
        $this->assertSame(TaskStatus::TODO, $task->getStatus());
        $this->assertSame('some-user-id', $task->getAssignedUserId()->getValue());
    }

    public function testCreateTaskWithCustomStatus(): void
    {
        $task = $this->factory->create(
            'Task in progress',
            'Task is already started',
            'user-123',
            TaskStatus::IN_PROGRESS
        );

        $this->assertSame(TaskStatus::IN_PROGRESS, $task->getStatus());
    }

    public function testCreateTaskGeneratesUniqueIds(): void
    {
        $taskOne = $this->factory->create('Task 1', 'Desc 1', 'user-1');
        $taskTwo = $this->factory->create('Task 2', 'Desc 2', 'user-2');

        $this->assertFalse($taskOne->getId()->equals($taskTwo->getId()));
    }

    public function testCreateTaskSetsTimestamps(): void
    {
        $task = $this->factory->create('Task', 'Desc', 'user-1');

        $this->assertNotNull($task->getCreatedAt());
        $this->assertNotNull($task->getUpdatedAt());
    }
}
