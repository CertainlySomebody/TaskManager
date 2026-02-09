<?php

declare(strict_types=1);

namespace App\Domain\Task\Event;

final class TaskCreatedEvent
{
    public function __construct(
        private readonly string $taskId,
        private readonly string $title,
        private readonly string $description,
        private readonly string $status,
        private readonly string $assignedUserId,
        private readonly \DateTimeImmutable $occurredAt
    ) {
    }

    public function getTaskId(): string
    {
        return $this->taskId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getAssignedUserId(): string
    {
        return $this->assignedUserId;
    }

    public function getOccurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
