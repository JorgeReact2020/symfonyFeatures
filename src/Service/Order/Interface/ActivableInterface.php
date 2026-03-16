<?php

declare(strict_types=1);

namespace App\Service\Order\Interface;

/**
 * Interface pour les produits ACTIVABLES (services/abonnements)
 *
 * SOLID Principle: Interface Segregation (ISP)
 * - Capacité optionnelle pour services et abonnements
 * - PhysicalProduct et DigitalProduct n'ont PAS à l'implémenter
 * - Un abonnement Netflix-like implémente cette interface
 */
interface ActivableInterface
{
    public function activate(string $customerId): void;

    public function deactivate(string $customerId): void;

    public function isActive(string $customerId): bool;

    public function getActivationDate(string $customerId): ?\DateTimeImmutable;

    public function getSubscriptionDuration(): ?int; // en jours, null = illimité
}
