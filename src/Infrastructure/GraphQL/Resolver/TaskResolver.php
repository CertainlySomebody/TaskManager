<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Resolver;

use App\Domain\Task\Entity\Task;
use App\Domain\Task\Repository\TaskRepositoryInterface;
use App\Domain\Task\ValueObject\TaskId;
use App\Domain\User\ValueObject\UserId;
use App\Infrastructure\Persistence\Doctrine\Repository\TaskEventStoreRepository;

class TaskResolver
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
        private TaskEventStoreRepository $eventStoreRepository
    ) {
    }

    public function allTasks(): array
    {
        return array_map([$this, 'formatTask'], $this->taskRepository->findAll());
    }

    public function userTasks(string $userId): array
    {
        $tasks = $this->taskRepository->findByAssignedUserId(new UserId($userId));

        return array_map([$this, 'formatTask'], $tasks);
    }

    public function task(string $id): ?array
    {
        $task = $this->taskRepository->findById(new TaskId($id));

        return $task ? $this->formatTask($task) : null;
    }

    public function taskHistory(string $taskId): array
    {
        $events = $this->eventStoreRepository->findByTaskId($taskId);

        return array_map(fn($event) => [
            'id' => $event->getId(),
            'taskId' => $event->getTaskId(),
            'eventType' => $event->getEventType(),
            'payload' => $event->getPayload(),
            'occurredAt' => $event->getOccurredAt()->format('c'),
        ], $events);
    }

    public function formatTask(Task $task): array
    {
        return [
            'id' => $task->getId()->getValue(),
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'status' => $task->getStatus()->value,
            'assignedUserId' => $task->getAssignedUserId()->getValue(),
            'createdAt' => $task->getCreatedAt()->format('c'),
            'updatedAt' => $task->getUpdatedAt()->format('c'),
        ];
    }
}
