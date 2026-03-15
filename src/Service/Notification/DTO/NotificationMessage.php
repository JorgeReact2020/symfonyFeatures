<?php

declare(strict_types=1);

namespace App\Service\Notification\DTO;

/**
 * Single Responsibility Principle: Cette classe a UNE seule responsabilité
 * - Transporter les données d'une notification
 * - C'est un DTO (Data Transfer Object) immutable
 * - Pas de logique métier, juste des données
 */
readonly class NotificationMessage
{
    /**
     * @param string $type Type de notification (alert, info, promotion)
     * @param string $title Titre court
     * @param string $content Contenu du message
     * @param string $recipient Destinataire (email, téléphone, username)
     * @param array<string, mixed> $metadata Données supplémentaires optionnelles
     */
    public function __construct(
        public string $type,
        public string $title,
        public string $content,
        public string $recipient,
        public array $metadata = []
    ) {
        // Validation basique inline
        if (!in_array($type, ['alert', 'info', 'promotion'])) {
            throw new \InvalidArgumentException(
                "Le type doit être 'alert', 'info' ou 'promotion'"
            );
        }

        if (empty($title) || empty($content) || empty($recipient)) {
            throw new \InvalidArgumentException(
                "Le titre, contenu et destinataire sont obligatoires"
            );
        }
    }

    /**
     * Helper pour créer une alerte
     */
    public static function alert(string $title, string $content, string $recipient): self
    {
        return new self('alert', $title, $content, $recipient);
    }

    /**
     * Helper pour créer une info
     */
    public static function info(string $title, string $content, string $recipient): self
    {
        return new self('info', $title, $content, $recipient);
    }

    /**
     * Helper pour créer une promotion
     */
    public static function promotion(string $title, string $content, string $recipient): self
    {
        return new self('promotion', $title, $content, $recipient);
    }
}
