<?php

declare(strict_types=1);

namespace App\Service\Notification\Channel;

use App\Service\Notification\DTO\NotificationMessage;
use App\Service\Notification\Interface\MessageFormatterInterface;
use App\Service\Notification\Interface\NotificationChannelInterface;

/**
 * Single Responsibility Principle: Envoie uniquement des SMS
 * Dependency Inversion Principle: Dépend de MessageFormatterInterface
 * Liskov Substitution Principle: Même interface que EmailChannel, donc interchangeable
 */
class SmsChannel implements NotificationChannelInterface
{
    public function __construct(
        private readonly MessageFormatterInterface $formatter
    ) {}

    public function send(NotificationMessage $message): bool
    {
        $formattedContent = $this->formatter->format($message);

        // Simuler l'envoi de SMS (dans la vraie vie, utiliser Twilio, etc.)
        echo "📱 SMS envoyé au {$message->recipient}\n";
        echo "Message: {$formattedContent}\n";
        echo str_repeat('-', 80) . "\n";

        return true;
    }

    public function getName(): string
    {
        return 'sms';
    }
}
