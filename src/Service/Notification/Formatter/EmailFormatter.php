<?php

declare(strict_types=1);

namespace App\Service\Notification\Formatter;

use App\Service\Notification\DTO\NotificationMessage;
use App\Service\Notification\Interface\MessageFormatterInterface;

/**
 * Single Responsibility Principle: Formate uniquement les messages pour Email
 * - Responsabilité unique : générer du HTML pour les emails
 */
class EmailFormatter implements MessageFormatterInterface
{
    public function format(NotificationMessage $message): string
    {
        $style = $this->getStyleForType($message->type);
        
        // Format HTML avec style selon le type
        return <<<HTML
        <div style="padding: 20px; background-color: {$style['background']}; color: {$style['color']}; border-radius: 8px;">
            <h2 style="margin: 0 0 10px 0;">{$style['icon']} {$message->title}</h2>
            <p style="margin: 0; line-height: 1.6;">{$message->content}</p>
            <hr style="margin: 15px 0; border: none; border-top: 1px solid {$style['color']};">
            <small style="opacity: 0.8;">Type: {$message->type}</small>
        </div>
        HTML;
    }
    
    private function getStyleForType(string $type): array
    {
        return match($type) {
            'alert' => [
                'background' => '#fee2e2',
                'color' => '#991b1b',
                'icon' => '⚠️'
            ],
            'info' => [
                'background' => '#dbeafe',
                'color' => '#1e40af',
                'icon' => 'ℹ️'
            ],
            'promotion' => [
                'background' => '#dcfce7',
                'color' => '#166534',
                'icon' => '🎁'
            ],
            default => [
                'background' => '#f3f4f6',
                'color' => '#374151',
                'icon' => '📧'
            ]
        };
    }
}
