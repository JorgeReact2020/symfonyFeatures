<?php

declare(strict_types=1);

namespace App\Service\Logger\DTO;

/**
 * Single Responsibility: Only holds log data
 */
final class LogEntry
{
    private \DateTimeImmutable $timestamp;

    public function __construct(
        private readonly string $level,
        private readonly string $message,
        private readonly array $context = []
    ) {
        $this->timestamp = new \DateTimeImmutable();
    }

    public function getLevel(): string
    {
        return $this->level;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function getTimestamp(): \DateTimeImmutable
    {
        return $this->timestamp;
    }
}
