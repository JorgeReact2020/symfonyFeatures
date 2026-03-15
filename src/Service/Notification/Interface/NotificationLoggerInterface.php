<?php

declare(strict_types=1);

namespace App\Service\Notification\Interface;

use App\Service\Notification\DTO\NotificationMessage;

/**
 * Interface Segregation Principle: Interface dédiée au logging
 * - Séparée de l'envoi et du formatage
 */
interface NotificationLoggerInterface
{
    /**
     * Logue une notification envoyée avec succès
     */
    public function logSuccess(NotificationMessage $message, string $channel): void;

    /**
     * Logue un échec d'envoi
     */
    public function logFailure(NotificationMessage $message, string $channel, string $reason): void;
}
