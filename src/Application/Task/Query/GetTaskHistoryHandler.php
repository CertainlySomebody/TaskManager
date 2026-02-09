<?php

declare(strict_types=1);

namespace App\Application\Task\Query;

use App\Infrastructure\Persistence\Doctrine\Repository\TaskEventStoreRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetTaskHistoryHandler
{
    public function __construct(
        private TaskEventStoreRepository $taskEventStoreRepository,
    ) {
    }

    public function __invoke(GetTaskHistoryQuery $query): array
    {
        return $this->taskEventStoreRepository->findByTaskId($query->taskId);
    }
}
