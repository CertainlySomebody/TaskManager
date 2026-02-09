<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Task\Entity;

use App\Domain\Task\Entity\Task;
use App\Domain\Task\ValueObject\TaskId;
use App\Domain\Task\ValueObject\TaskStatus;
use App\Domain\User\ValueObject\UserId;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testCreateTask(): void
    {
        $taskId = new TaskId('test-task-id');
        $userId = new UserId('test-user-id');

        $task = new Task(
            $taskId,
            'My Task',
            'My Description',
            TaskStatus::TODO,
            $userId
        );

        $originalUpdatedAt = $task->getUpdatedAt();

        usleep(1000);

        $task->changeStatus(TaskStatus::IN_PROGRESS);

        $this->assertSame(TaskStatus::IN_PROGRESS, $task->getStatus());
        $this->assertGreaterThanOrEqual($originalUpdatedAt, $task->getUpdatedAt());
    }

    public function testTaskIdGeneration(): void
    {
        $task = new Task(
            new TaskId(),
            'Task',
            'Desc',
            TaskStatus::TODO,
            new UserId()
        );

        $this->assertNotEmpty($task->getId()->getValue());
        $this->assertSame(36, strlen($task->getId()->getValue()));
    }
}
