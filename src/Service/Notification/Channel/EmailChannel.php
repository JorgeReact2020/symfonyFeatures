<?php

declare(strict_types=1);

namespace App\Service\Notification\Channel;

use App\Service\Notification\DTO\NotificationMessage;
use App\Service\Notification\Interface\MessageFormatterInterface;
use App\Service\Notification\Interface\NotificationChannelInterface;

/**
 * Single Responsibility Principle: Envoie uniquement des emails
 * Dependency Inversion Principle: Dépend de MessageFormatterInterface, pas d'une classe concrète
 * Liskov Substitution Principle: Peut être substitué par n'importe quel NotificationChannelInterface
 */
class EmailChannel implements NotificationChannelInterface
{
    public function __construct(
        private readonly MessageFormatterInterface $formatter
    ) {}

    public function send(NotificationMessage $message): bool
    {
        // Formater le message
        $formattedContent = $this->formatter->format($message);

        // Simuler l'envoi d'email (dans la vraie vie, utiliser Symfony Mailer)
        echo "📧 Email envoyé à {$message->recipient}\n";
        echo "Sujet: {$message->title}\n";
        echo "Contenu HTML:\n{$formattedContent}\n";
        echo str_repeat('-', 80) . "\n";

        return true;
    }

    public function getName(): string
    {
        return 'email';
    }
}