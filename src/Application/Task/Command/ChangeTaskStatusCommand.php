<?php

declare(strict_types=1);

namespace App\Application\Task\Command;

final class ChangeTaskStatusCommand
{
    public function __construct(
        public readonly string $taskId,
        public readonly string $newStatus
    ) {
    }
}
