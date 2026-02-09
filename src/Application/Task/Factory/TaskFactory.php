<?php

declare(strict_types=1);

namespace App\Application\Task\Factory;

use App\Domain\Task\Entity\Task;
use App\Domain\Task\ValueObject\TaskId;
use App\Domain\Task\ValueObject\TaskStatus;
use App\Domain\User\ValueObject\UserId;

class TaskFactory
{
    public function create(
        string $title,
        string $description,
        string $assignedUserId,
        TaskStatus $status = TaskStatus::TODO
    ): Task
    {
        return new Task(
            new TaskId(),
            $title,
            $description,
            $status,
            new UserId($assignedUserId)
        );
    }
}
