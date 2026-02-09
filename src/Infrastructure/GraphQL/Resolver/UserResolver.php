<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Resolver;

use App\Domain\User\Repository\UserRepositoryInterface;

class UserResolver
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function allUsers(): array
    {
        return array_map(fn($user) => [
            'id' => $user->getId()->getValue(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
            'externalId' => $user->getExternalId(),
        ], $this->userRepository->findAll());
    }
}
