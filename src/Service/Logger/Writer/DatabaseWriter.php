<?php

declare(strict_types=1);

namespace App\Service\Logger\Writer;

use App\Service\Logger\DTO\LogEntry;
use App\Service\Logger\Interface\LogWriterInterface;
use Doctrine\DBAL\Connection;

/**
 * Single Responsibility: Only writes to database
 * Liskov Substitution: Can replace any LogWriterInterface
 */
class DatabaseWriter implements LogWriterInterface
{
    public function __construct(private readonly Connection $connection) {}

    public function write(LogEntry $entry): bool
    {
        try {
            $this->connection->insert('logs', [
                'level' => $entry->getLevel(),
                'message' => $entry->getMessage(),
                'context' => json_encode($entry->getContext()),
                'created_at' => $entry->getTimestamp()->format('Y-m-d H:i:s'),
            ]);

            return true;
        } catch (\Exception) {
            return false;
        }
    }

    public function getName(): string
    {
        return 'database';
    }
}
