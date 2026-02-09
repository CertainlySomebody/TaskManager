<?php

declare(strict_types=1);

namespace App\Application\Task\Strategy;

use App\Domain\Task\ValueObject\TaskStatus;

interface TaskStatusTransitionStrategyInterface
{
    public function canTransition(TaskStatus $from, TaskStatus $to): bool;
}
