<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use Symfony\Component\Uid\Uuid;

final class UserId
{
    public function __construct(private ?string $value = null)
    {
        $this->value = $value ?? Uuid::v4()->toRfc4122();
    }

    /**
     * @return string
     */
    public function getValue(): string
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
