<?php

declare(strict_types=1);

namespace App\Domain\Task\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'task_event_store')]
class TaskEventStore
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;
    public function __construct(
        #[ORM\Column(name: 'task_id', type: 'string', length: 36)]
        private string $taskId,

        #[ORM\Column(name: 'event_type', type: 'string', length: 100)]
        private string $eventType,

        #[ORM\Column(type: 'json')]
        private array $payload,

        #[ORM\Column(name: 'occurred_at', type: 'datetime_immutable')]
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
