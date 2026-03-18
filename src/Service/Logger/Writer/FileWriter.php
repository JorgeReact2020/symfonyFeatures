<?php

declare(strict_types=1);

namespace App\Service\Logger\Writer;

use App\Service\Logger\DTO\LogEntry;
use App\Service\Logger\Interface\LogWriterInterface;

/**
 * Single Responsibility: Only writes to files
 * Liskov Substitution: Can replace any LogWriterInterface
 */
class FileWriter implements LogWriterInterface
{
    public function __construct(private readonly string $filePath) {}

    public function write(LogEntry $entry): bool
    {
        $line = sprintf(
            "[%s] %s: %s %s\n",
            $entry->getTimestamp()->format('Y-m-d H:i:s'),
            $entry->getLevel(),
            $entry->getMessage(),
            $entry->getContext() ? json_encode($entry->getContext()) : ''
        );

        return file_put_contents($this->filePath, $line, FILE_APPEND) !== false;
    }

    public function getName(): string
    {
        return 'file';
    }
}
