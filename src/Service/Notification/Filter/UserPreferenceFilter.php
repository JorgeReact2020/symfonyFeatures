<?php

declare(strict_types=1);

namespace App\Service\Notification\Filter;

use App\Service\Notification\DTO\NotificationMessage;
use App\Service\Notification\Interface\NotificationFilterInterface;

/**
 * Single Responsibility Principle: Vérifie uniquement les préférences utilisateur
 * - Ne logne pas
 * - N'envoie pas
 * - Juste filtre
 *
 * Open/Closed Principle: Pour ajouter un nouveau type de filtre (ex: rate limiting),
 * on créerait une nouvelle classe qui implémente NotificationFilterInterface
 */
class UserPreferenceFilter implements NotificationFilterInterface
{
    /**
     * Simulation d'une base de données de préférences utilisateur
     * Dans la vraie vie, cela viendrait d'une base de données
     */
    private array $userPreferences = [
        'user@example.com' => [
            'email' => true,
            'sms' => false,
            'slack' => true
        ],
        '+33612345678' => [
            'email' => true,
            'sms' => true,
            'slack' => false
        ],
        '@john.doe' => [
            'email' => true,
            'sms' => true,
            'slack' => true
        ]
    ];

    public function canSend(NotificationMessage $message, string $channel): bool
    {
        // Si pas de préférences définies, on autorise par défaut
        if (!isset($this->userPreferences[$message->recipient])) {
            return true;
        }

        $preferences = $this->userPreferences[$message->recipient];

        // Vérifier si le canal est autorisé
        return $preferences[$channel] ?? false;
    }
}
