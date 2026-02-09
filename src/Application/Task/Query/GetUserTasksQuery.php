<?php

declare(strict_types=1);

namespace App\Application\Task\Query;

final class GetUserTasksQuery
{
    public function __construct(
        public readonly string $userId
    ) {
    }
}
