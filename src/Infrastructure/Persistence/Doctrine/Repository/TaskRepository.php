<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Task\Entity\Task;
use App\Domain\Task\Repository\TaskRepositoryInterface;
use App\Domain\Task\ValueObject\TaskId;
use App\Domain\User\ValueObject\UserId;
use Doctrine\ORM\EntityManagerInterface;

class TaskRepository implements TaskRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function findById(TaskId $id): ?Task
    {
        return $this->entityManager->find(Task::class, $id->getValue());
    }

    public function findByAssignedUserId(UserId $assignedUserId): array
    {
        return $this->entityManager->getRepository(Task::class)
            ->findBy(['assignedUserId' => $assignedUserId->getValue()]);
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(Task::class)->findAll();
    }

    public function save(Task $task): void
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }
}
