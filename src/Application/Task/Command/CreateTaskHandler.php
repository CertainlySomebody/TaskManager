<?php

declare(strict_types=1);

namespace App\Application\Task\Command;

use App\Domain\Task\Entity\Task;
use App\Domain\Task\Event\TaskCreatedEvent;
use App\Domain\Task\Repository\TaskRepositoryInterface;
use App\Domain\Task\ValueObject\TaskId;
use App\Domain\Task\ValueObject\TaskStatus;
use App\Domain\User\ValueObject\UserId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class CreateTaskHandler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
        private MessageBusInterface $eventBus,
    ) {
    }

    public function __invoke(CreateTaskCommand $command): Task
    {
        $taskId = new TaskId();
        $status = TaskStatus::TODO;

        $task = new Task(
            $taskId,
            $command->title,
            $command->description,
            $status,
            new UserId($command->assignedUserId)
        );

        $this->taskRepository->save($task);

        $this->eventBus->dispatch(new TaskCreatedEvent(
            $taskId->getValue(),
            $command->title,
            $command->description,
            $status->value,
            $command->assignedUserId,
            new \DateTimeImmutable()
        ));

        return $task;
    }
}
