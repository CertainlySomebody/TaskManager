<?php

declare(strict_types=1);

namespace App\Domain\Task\Entity;

use App\Domain\Task\ValueObject\TaskId;
use App\Domain\Task\ValueObject\TaskStatus;
use App\Domain\User\ValueObject\UserId;

class Task
{
    public function __construct(
        private TaskId $id,
        private string $title,
        private string $description,
        private TaskStatus $status,
        private UserId $assignedUserId,
        private ?\DateTimeImmutable $createdAt = null,
        private ?\DateTimeImmutable $updatedAt = null,
    )
    {
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->updatedAt = $createdAt;
    }

    /**
     * @return TaskId
     */
    public function getId(): TaskId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return TaskStatus
     */
    public function getStatus(): TaskStatus
    {
        return $this->status;
    }

    /**
     * @return UserId
     */
    public function getAssignedUserId(): UserId
    {
        return $this->assignedUserId;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function changeStatus(TaskStatus $newStatus): void
    {
        $this->status = $newStatus;
        $this->updatedAt = new \DateTimeImmutable();
    }
}
