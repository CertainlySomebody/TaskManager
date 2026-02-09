<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSourcing;

use App\Domain\Task\Entity\TaskEventStore;
use App\Domain\Task\Event\TaskCreatedEvent;
use App\Infrastructure\Persistence\Doctrine\Repository\TaskEventStoreRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class TaskEventSubscriber
{
    public function __construct(
        private TaskEventStoreRepository $eventStoreRepository,
    ) {
    }

    public function __invoke(TaskCreatedEvent $event): void
    {
        $entry = new TaskEventStore(
            $event->getTaskId(),
            'task_created',
            [
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'status' => $event->getStatus(),
                'assigned_user_id' => $event->getAssignedUserId(),
            ],
            $event->getOccurredAt()
        );

        $this->eventStoreRepository->save($entry);
    }
}
