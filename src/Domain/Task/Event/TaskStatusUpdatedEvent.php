<?php

declare(strict_types=1);

namespace App\Domain\Task\Event;

final class TaskStatusUpdatedEvent
{
    public function __construct(
        private readonly string $taskId,
        private readonly string $oldStatus,
        private readonly string $newStatus,
        private readonly \DateTimeImmutable $occurredAt
    ) {
    }

    public function getTaskId(): string
    {
        return $this->taskId;
    }

    public function getOldStatus(): string
    {
        return $this->oldStatus;
    }

    public function getNewStatus(): string
    {
        return $this->newStatus;
    }

    public function getOccurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
