<?php

declare(strict_types=1);

namespace App\Service\Notification\Interface;

use App\Service\Notification\DTO\NotificationMessage;

/**
 * Interface Segregation Principle: Interface dédiée à l'envoi
 * - Seulement 2 méthodes liées à l'envoi de notification
 */
interface NotificationChannelInterface
{
    /**
     * Envoie une notification via ce canal
     */
    public function send(NotificationMessage $message): bool;

    /**
     * Retourne le nom du canal
     */
    public function getName(): string;
}
