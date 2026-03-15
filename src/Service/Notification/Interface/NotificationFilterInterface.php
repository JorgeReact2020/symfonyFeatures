<?php

declare(strict_types=1);

namespace App\Service\Notification\Interface;

use App\Service\Notification\DTO\NotificationMessage;

/**
 * Interface Segregation Principle: Interface dédiée au filtrage
 * - Seulement la responsabilité de décider si on peut envoyer
 */
interface NotificationFilterInterface
{
    /**
     * Détermine si une notification peut être envoyée
     */
    public function canSend(NotificationMessage $message, string $channel): bool;
}
