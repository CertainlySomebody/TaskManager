<?php

declare(strict_types=1);

namespace App\Domain\Task\Entity;

class TaskEventStore
{
    private ?int $id = null;
    public function __construct(
        private string $taskId,
        private string $eventType,
        private array $payload,
        private ?\DateTimeImmutable $occurredAt = null,
    ) {
        $this->occurredAt = $occurredAt ?? new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaskId(): string
    {
        return $this->taskId;
    }

    public function getEventType(): string
    {
        return $this->eventType;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getOccurredAt(): ?\DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
