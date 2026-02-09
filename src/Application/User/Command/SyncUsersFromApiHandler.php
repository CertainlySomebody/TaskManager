<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Infrastructure\ExternalApi\JsonPlaceholder\Client;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\ValueObject\UserId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SyncUsersFromApiHandler
{
    public function __construct(
        private Client $jsonPlaceholderClient,
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function __invoke(SyncUsersFromApiCommand $command): int
    {
        $usersData = $this->jsonPlaceholderClient->fetchUsers();
        $synced = 0;

        foreach ($usersData as $userData) {
            $existing = $this->userRepository->findByExternalId($userData['id']);

            if ($existing) {
                continue;
            }

            $user = new User(
                new UserId(),
                $userData['name'],
                $userData['email'],
                $userData['phone'],
                $userData['username'],
                ['ROLE_USER'],
                $userData['id'],
            );

            $this->userRepository->save($user);
            $synced++;
        }

        return $synced;
    }
}
