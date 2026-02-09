<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Mutation;

use App\Application\User\Command\SyncUsersFromApiCommand;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class UserMutation
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function syncUsers(): int
    {
        $envelope = $this->messageBus->dispatch(new SyncUsersFromApiCommand());

        return $envelope->last(HandledStamp::class)->getResult();
    }
}
