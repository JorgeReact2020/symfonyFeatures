<?php

declare(strict_types=1);

namespace App\Service\Notification;

use App\Service\Notification\DTO\NotificationMessage;
use App\Service\Notification\Interface\NotificationChannelInterface;
use App\Service\Notification\Interface\NotificationFilterInterface;
use App\Service\Notification\Interface\NotificationLoggerInterface;

/**
 * Single Responsibility Principle: Orchestre l'envoi de notifications
 * - N'envoie pas lui-même (délègue aux channels)
 * - Ne formate pas (délègue aux formatters)
 * - Ne logue pas directement (délègue au logger)
 * - Ne filtre pas (délègue au filter)
 *
 * Open/Closed Principle: Pour ajouter un nouveau canal, on l'injecte juste dans le constructeur
 * - Pas besoin de modifier cette classe
 * - Extension par injection, pas par modification
 *
 * Dependency Inversion Principle: Dépend uniquement des interfaces
 * - NotificationChannelInterface (pas EmailChannel, SmsChannel, etc.)
 * - NotificationFilterInterface
 * - NotificationLoggerInterface
 *
 * Liskov Substitution Principle: Tous les channels sont interchangeables
 * - Peut utiliser n'importe quelle implémentation de NotificationChannelInterface
 */
class NotificationService
{
    /**
     * @param iterable<NotificationChannelInterface> $channels
     */
    public function __construct(
        private readonly iterable $channels,
        private readonly NotificationFilterInterface $filter,
        private readonly NotificationLoggerInterface $logger
    ) {}

    /**
     * Envoie une notification sur un canal spécifique
     */
    public function send(NotificationMessage $message, string $channelName): bool
    {
        $channel = $this->findChannel($channelName);

        if (!$channel) {
            $this->logger->logFailure($message, $channelName, 'Canal non trouvé');
            return false;
        }

        // Vérifier si l'envoi est autorisé
        if (!$this->filter->canSend($message, $channelName)) {
            $this->logger->logFailure($message, $channelName, 'Bloqué par les préférences utilisateur');
            echo "❌ Notification bloquée par les préférences utilisateur pour le canal '{$channelName}'\n\n";
            return false;
        }

        // Envoyer
        try {
            $result = $channel->send($message);

            if ($result) {
                $this->logger->logSuccess($message, $channelName);
            } else {
                $this->logger->logFailure($message, $channelName, 'Échec envoi');
            }

            return $result;

        } catch (\Exception $e) {
            $this->logger->logFailure($message, $channelName, $e->getMessage());
            return false;
        }
    }

    /**
     * Envoie une notification sur TOUS les canaux disponibles
     */
    public function sendToAll(NotificationMessage $message): array
    {
        $results = [];

        foreach ($this->channels as $channel) {
            $channelName = $channel->getName();
            $results[$channelName] = $this->send($message, $channelName);
        }

        return $results;
    }

    /**
     * Trouve un canal par son nom
     */
    private function findChannel(string $name): ?NotificationChannelInterface
    {
        foreach ($this->channels as $channel) {
            if ($channel->getName() === $name) {
                return $channel;
            }
        }

        return null;
    }

    /**
     * Liste tous les canaux disponibles
     */
    public function getAvailableChannels(): array
    {
        $channels = [];

        foreach ($this->channels as $channel) {
            $channels[] = $channel->getName();
        }

        return $channels;
    }
}
