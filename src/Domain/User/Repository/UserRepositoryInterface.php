<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;
use App\Domain\User\ValueObject\UserId;

interface UserRepositoryInterface
{
    public function findById(UserId $id): ?User;
    public function findByEmail(string $email): ?User;
    public function findByExternalId(int $externalId): ?User;
    public function save(User $user): void;

    /** @return User[] */
    public function findAll(): array;
    public function findByApiToken(string $token): ?User;
}
