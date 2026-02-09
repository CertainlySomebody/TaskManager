<?php

declare(strict_types=1);

namespace App\Domain\Task\Repository;

use App\Domain\Task\Entity\Task;
use App\Domain\Task\ValueObject\TaskId;
use App\Domain\User\ValueObject\UserId;

interface TaskRepositoryInterface
{
    public function findById(TaskId $id): ?Task;
    public function findByAssignedUserId(UserId $assignedUserId): array;
    public function findAll(): array;
    public function save(Task $task): void;
}
