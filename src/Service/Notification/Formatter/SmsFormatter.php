<?php

declare(strict_types=1);

namespace App\Service\Notification\Formatter;

use App\Service\Notification\DTO\NotificationMessage;
use App\Service\Notification\Interface\MessageFormatterInterface;

/**
 * Single Responsibility Principle: Formate uniquement les messages pour SMS
 * - Responsabilité unique : générer du texte court pour SMS (max 160 caractères)
 */
class SmsFormatter implements MessageFormatterInterface
{
    private const MAX_LENGTH = 160;

    public function format(NotificationMessage $message): string
    {
        $icon = $this->getIconForType($message->type);

        // Format court pour SMS
        $text = "{$icon} {$message->title}: {$message->content}";

        // Tronquer si trop long
        if (mb_strlen($text) > self::MAX_LENGTH) {
            $text = mb_substr($text, 0, self::MAX_LENGTH - 3) . '...';
        }

        return $text;
    }

    private function getIconForType(string $type): string
    {
        return match($type) {
            'alert' => '[ALERTE]',
            'info' => '[INFO]',
            'promotion' => '[PROMO]',
            default => '[NOTIF]'
        };
    }
}
