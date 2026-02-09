<?php

declare(strict_types=1);

namespace App\Domain\Task\ValueObject;

use Symfony\Component\Uid\Uuid;

final class TaskId
{
    public function __construct(private ?string $value = null)
    {
        $this->value = $value ?? Uuid::v4()->toRfc4122();
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
