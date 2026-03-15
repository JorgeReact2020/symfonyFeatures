<?php

declare(strict_types=1);

namespace App\Service\Notification\Interface;

use App\Service\Notification\DTO\NotificationMessage;

/**
 * Interface Segregation Principle: Interface spécifique au formatage
 * - Responsabilité unique : formater un message
 */
interface MessageFormatterInterface
{
    /**
     * Formate le message selon le format du canal
     */
    public function format(NotificationMessage $message): string;
}