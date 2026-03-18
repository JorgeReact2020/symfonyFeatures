<?php

declare(strict_types=1);

namespace App\Service\Logger\Interface;

use App\Service\Logger\DTO\LogEntry;

/**
 * Interface Segregation: Focused interface for writing logs
 * Dependency Inversion: High-level modules depend on this abstraction
 */
interface LogWriterInterface
{
    /**
     * Writes the log entry to the destination
     */
    public function write(LogEntry $entry): bool;

    /**
     * Returns the writer identifier
     */
    public function getName(): string;
}
