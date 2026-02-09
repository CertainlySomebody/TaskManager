<?php

declare(strict_types=1);

namespace App\Domain\Task\Entity;

use App\Domain\Task\ValueObject\TaskId;
use App\Domain\Task\ValueObject\TaskStatus;
use App\Domain\User\ValueObject\UserId;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tasks')]
class Task
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;
    #[ORM\Column(type: 'string', length: 20)]
    private string $status;
    #[ORM\Column(name: 'assigned_user_id', type: 'string', length: 36)]
    private string $assignedUserId;
    public function __construct(
        TaskId $taskId,

        #[ORM\Column(type: 'string', length: 255)]
        private string $title,

        #[ORM\Column(type: 'text')]
        private string $description,

        TaskStatus $taskStatus,
        UserId $userId,

        #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
        private ?\DateTimeImmutable $createdAt = null,

        #[ORM\Column(name: 'updated_at', type: 'datetime_immutable')]
        private ?\DateTimeImmutable $updatedAt = null,
    )
    {
        $this->id = $taskId->getValue();
        $this->status = $taskStatus->value;
        $this->assignedUserId = $userId->getValue();
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->updatedAt = $createdAt;
    }

    /**
     * @return TaskId
     */
    public function getId(): TaskId
    {
        return new TaskId($this->id);
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
        return TaskStatus::from($this->status);
    }

    /**
     * @return UserId
     */
    public function getAssignedUserId(): UserId
    {
        return new UserId($this->assignedUserId);
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
        $this->status = $newStatus->value;
        $this->updatedAt = new \DateTimeImmutable();
    }
}
