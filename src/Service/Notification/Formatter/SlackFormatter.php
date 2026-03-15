<?php

declare(strict_types=1);

namespace App\Service\Notification\Formatter;

use App\Service\Notification\DTO\NotificationMessage;
use App\Service\Notification\Interface\MessageFormatterInterface;



/**
 * Single Responsibility Principle: Formate uniquement les messages pour Slack
 * - Responsabilité unique : générer du Markdown pour Slack
 */
class SlackFormatter implements MessageFormatterInterface
{
   public function format(NotificationMessage $message): string
    {
        $emoji = $this->getEmojiForType($message->type);

        // Format Markdown Slack
        return <<<MARKDOWN
{$emoji} *{$message->title}*

{$message->content}

_Type: {$message->type}_
MARKDOWN;
    }

    private function getEmojiForType(string $type): string
    {
        return match($type) {
            'alert' => ':warning:',
            'info' => ':information_source:',
            'promotion' => ':gift:',
            default => ':bell:'
        };
    }
}
