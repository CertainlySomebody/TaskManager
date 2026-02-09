<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Mutation;

use App\Application\Task\Command\ChangeTaskStatusCommand;
use App\Application\Task\Command\CreateTaskCommand;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class TaskMutation
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function createTask(string $title, string $description, string $assignedUserId): array
    {
        $envelope = $this->messageBus->dispatch(
            new CreateTaskCommand($title, $description, $assignedUserId));
        $task = $envelope->last(HandledStamp::class)->getResult();

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

    public function changeTaskStatus(string $taskId, string $newStatus): bool
    {
        $this->messageBus->dispatch(new ChangeTaskStatusCommand($taskId, $newStatus));

        return true;
    }
}
