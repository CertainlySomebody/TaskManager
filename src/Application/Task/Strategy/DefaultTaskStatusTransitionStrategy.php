<?php

declare(strict_types=1);

namespace App\Application\Task\Strategy;

use App\Domain\Task\ValueObject\TaskStatus;

class DefaultTaskStatusTransitionStrategy implements TaskStatusTransitionStrategyInterface
{
    private const ALLOWED_TRANSITIONS = [
        'todo' => ['in_progress'],
        'in_progress' => ['done', 'todo'],
        'done' => []
    ];

    public function canTransition(TaskStatus $from, TaskStatus $to): bool
    {
        $allowed = self::ALLOWED_TRANSITIONS[$from->value] ?? [];

        return in_array($to->value, $allowed, true);
    }
}
