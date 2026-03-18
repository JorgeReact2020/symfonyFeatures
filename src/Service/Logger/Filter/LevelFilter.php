<?php

declare(strict_types=1);

namespace App\Service\Logger\Filter;

use App\Service\Logger\DTO\LogEntry;
use App\Service\Logger\Interface\LogFilterInterface;

/**
 * Single Responsibility: Only filters by level
 * Open/Closed: Can create new filters without modifying this one
 */
class LevelFilter implements LogFilterInterface
{
    private const LEVELS = [
        'INFO' => 1,
        'WARNING' => 2,
        'ERROR' => 3,
    ];

    public function __construct(private readonly string $minLevel = 'INFO') {}

    public function shouldLog(LogEntry $entry): bool
    {
        $entryPriority = self::LEVELS[$entry->getLevel()] ?? 0;
        $minPriority = self::LEVELS[$this->minLevel] ?? 0;

        return $entryPriority >= $minPriority;
    }
}
