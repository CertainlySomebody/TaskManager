<?php

declare(strict_types=1);

namespace App\Application\Task\Query;

use App\Domain\Task\Repository\TaskRepositoryInterface;
use App\Domain\User\ValueObject\UserId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetUserTasksHandler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
    ) {
    }

    public function __invoke(GetUserTasksQuery $query): array
    {
        return $this->taskRepository->findByAssignedUserId(new UserId($query->userId));
    }
}
