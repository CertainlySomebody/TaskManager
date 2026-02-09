<?php

declare(strict_types=1);

namespace App\Application\Task\Query;

use App\Domain\Task\Repository\TaskRepositoryInterface;

class GetAllTasksHandler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
    ) {
    }

    public function __invoke(GetAllTasksQuery $query): array
    {
        return $this->taskRepository->findAll();
    }
}
