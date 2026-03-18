<?php

declare(strict_types=1);

namespace App\Service\Logger\Interface;

use App\Service\Logger\DTO\LogEntry;

/**
 * Interface Segregation: Focused interface for filtering
 * Single Responsibility: Only determines if log should pass
 */
interface LogFilterInterface
{
    /**
     * Determines if the log entry should be written
     */
    public function shouldLog(LogEntry $entry): bool;
}
