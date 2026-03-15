<?php

declare(strict_types=1);

namespace App\Service\Notification\Logger;

use App\Service\Notification\DTO\NotificationMessage;
use App\Service\Notification\Interface\NotificationLoggerInterface;
use Psr\Log\LoggerInterface;

/**
 * Single Responsibility Principle: Ne fait QUE du logging
 * - N'envoie pas de notifications
 * - N'applique pas de filtres
 * - Juste du logging
 *
 * Dependency Inversion Principle: Dépend de Psr\Log\LoggerInterface (abstraction)
 */
class NotificationLogger implements NotificationLoggerInterface
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {}

    public function logSuccess(NotificationMessage $message, string $channel): void
    {
        $this->logger->info('Notification envoyée avec succès', [
            'channel' => $channel,
            'type' => $message->type,
            'recipient' => $message->recipient,
            'title' => $message->title
        ]);
    }

    public function logFailure(NotificationMessage $message, string $channel, string $reason): void
    {
        $this->logger->error('Échec envoi notification', [
            'channel' => $channel,
            'type' => $message->type,
            'recipient' => $message->recipient,
            'title' => $message->title,
            'reason' => $reason
        ]);
    }
}
