<?php

declare(strict_types=1);

namespace App\Application\Task\Command;

use App\Application\Task\Strategy\TaskStatusTransitionStrategyInterface;
use App\Domain\Task\Event\TaskStatusUpdatedEvent;
use App\Domain\Task\Repository\TaskRepositoryInterface;
use App\Domain\Task\ValueObject\TaskId;
use App\Domain\Task\ValueObject\TaskStatus;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class ChangeTaskStatusHandler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
        private TaskStatusTransitionStrategyInterface $transitionStrategy,
        private MessageBusInterface $eventBus,
    ) {
    }

    public function __invoke(ChangeTaskStatusCommand $command): void
    {
        $task = $this->taskRepository->findById(new TaskId($command->taskId));

        if (!$task) {
            throw new \InvalidArgumentException('Task not found');
        }

        $newStatus = TaskStatus::from($command->newStatus);
        $oldStatus = $task->getStatus();

        if (!$this->transitionStrategy->canTransition($oldStatus, $newStatus)) {
            throw new \DomainException(
                sprintf('Cannot transition from "%s" to "%s".', $oldStatus->value, $newStatus->value)
            );
        }

        $task->changeStatus($newStatus);
        $this->taskRepository->save($task);

        $this->eventBus->dispatch(new TaskStatusUpdatedEvent(
            $command->taskId,
            $oldStatus->value,
            $newStatus->value,
            new \DateTimeImmutable()
        ));
    }
}
