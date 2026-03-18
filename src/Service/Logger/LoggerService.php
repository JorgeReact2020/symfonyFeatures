<?php

declare(strict_types=1);

namespace App\Service\Logger;

use App\Service\Logger\DTO\LogEntry;
use App\Service\Logger\Interface\LogFilterInterface;
use App\Service\Logger\Interface\LogWriterInterface;

/**
 * Single Responsibility: Orchestrates logging (doesn't write or filter itself)
 * Open/Closed: Add new writers without modifying this class
 * Dependency Inversion: Depends on interfaces, not concrete classes
 * Liskov Substitution: Any writer can replace another
 */
class LoggerService
{
    /**
     * @param iterable<LogWriterInterface> $writers
     */
    public function __construct(
        private readonly iterable $writers,
        private readonly LogFilterInterface $filter
    ) {}

    public function log(string $level, string $message, array $context = []): void
    {
        $entry = new LogEntry($level, $message, $context);

        if (!$this->filter->shouldLog($entry)) {
            return;
        }

        foreach ($this->writers as $writer) {
            $writer->write($entry);
        }
    }

    public function info(string $message, array $context = []): void
    {
        $this->log('INFO', $message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->log('WARNING', $message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->log('ERROR', $message, $context);
    }
}
