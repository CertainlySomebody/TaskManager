<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Task\Entity\TaskEventStore;
use Doctrine\ORM\EntityManagerInterface;

class TaskEventStoreRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function save(TaskEventStore $taskEventStore): void
    {
        $this->entityManager->persist($taskEventStore);
        $this->entityManager->flush();
    }

    public function findByTaskId(string $taskId): array
    {
        return $this->entityManager->getRepository(TaskEventStore::class)
            ->findBy(['taskId' => $taskId], ['occurredAt' => 'ASC']);
    }
}
