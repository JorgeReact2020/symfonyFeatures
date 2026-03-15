<?php

declare(strict_types=1);

namespace App\Service\Notification\Channel;

use App\Service\Notification\DTO\NotificationMessage;
use App\Service\Notification\Interface\MessageFormatterInterface;
use App\Service\Notification\Interface\NotificationChannelInterface;

/**
 * Single Responsibility Principle: Envoie uniquement des messages Slack
 * Dependency Inversion Principle: Dépend de MessageFormatterInterface
 * Liskov Substitution Principle: Même comportement que les autres channels
 */
class SlackChannel implements NotificationChannelInterface
{
    public function __construct(
        private readonly MessageFormatterInterface $formatter
    ) {}

    public function send(NotificationMessage $message): bool
    {
        $formattedContent = $this->formatter->format($message);

        // Simuler l'envoi vers Slack (dans la vraie vie, utiliser l'API Slack)
        echo "💬 Message Slack envoyé\n";
        echo "Channel/User: {$message->recipient}\n";
        echo "Message Markdown:\n{$formattedContent}\n";
        echo str_repeat('-', 80) . "\n";

        return true;
    }

    public function getName(): string
    {
        return 'slack';
    }
}
