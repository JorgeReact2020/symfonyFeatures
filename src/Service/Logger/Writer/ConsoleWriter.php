<?php

declare(strict_types=1);

namespace App\Service\Logger\Writer;

use App\Service\Logger\DTO\LogEntry;
use App\Service\Logger\Interface\LogWriterInterface;

/**
 * Single Responsibility: Only writes to console
 * Liskov Substitution: Can replace any LogWriterInterface
 */
class ConsoleWriter implements LogWriterInterface
{
    public function write(LogEntry $entry): bool
    {
        $colors = [
            'INFO' => "\033[32m",     // Green
            'WARNING' => "\033[33m",  // Yellow
            'ERROR' => "\033[31m",    // Red
        ];
        $reset = "\033[0m";
        $color = $colors[$entry->getLevel()] ?? '';

        echo sprintf(
            "%s[%s] %s:%s %s %s\n",
            $color,
            $entry->getTimestamp()->format('H:i:s'),
            $entry->getLevel(),
            $reset,
            $entry->getMessage(),
            $entry->getContext() ? json_encode($entry->getContext()) : ''
        );

        return true;
    }

    public function getName(): string
    {
        return 'console';
    }
}
