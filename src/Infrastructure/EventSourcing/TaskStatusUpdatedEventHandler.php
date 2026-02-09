<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSourcing;

use App\Domain\Task\Entity\TaskEventStore;
use App\Domain\Task\Event\TaskStatusUpdatedEvent;
use App\Infrastructure\Persistence\Doctrine\Repository\TaskEventStoreRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class TaskStatusUpdatedEventHandler
{
    public function __construct(
        private TaskEventStoreRepository $eventStoreRepository
    ) {
    }

    public function __invoke(TaskStatusUpdatedEvent $event): void
    {
        $entry = new TaskEventStore(
            $event->getTaskId(),
            'task_status_updated',
            [
                'old_status' => $event->getOldStatus(),
                'new_status' => $event->getNewStatus(),
            ],
            $event->getOccurredAt()
        );

        $this->eventStoreRepository->save($entry);
    }
}
