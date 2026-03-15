<?php

declare(strict_types=1);

namespace App\Service\Payment\ValueObject;

/**
 * Value Object représentant les contraintes d'une méthode de paiement
 *
 * Chaque PaymentMethod déclare ses propres contraintes :
 * - Montants minimum et maximum acceptés
 * - Devises supportées
 *
 * Principe SOLID respecté :
 * - SRP : Encapsule uniquement les règles de contraintes
 * - OCP : Extensible (peut ajouter refundDelayDays, etc.)
 * - LSP : Immuable (readonly) garantit cohérence
 */
final readonly class PaymentConstraints
{
    /**
     * @param float $minAmount Montant minimum accepté (inclus)
     * @param float $maxAmount Montant maximum accepté (inclus)
     * @param array<string> $supportedCurrencies Liste des codes ISO 4217 supportés (ex: ['EUR', 'USD'])
     */
    public function __construct(
        public float $minAmount,
        public float $maxAmount,
        public array $supportedCurrencies,
    ) {
        if ($minAmount < 0) {
            throw new \InvalidArgumentException('Minimum amount cannot be negative');
        }

        if ($maxAmount <= $minAmount) {
            throw new \InvalidArgumentException('Maximum amount must be greater than minimum amount');
        }

        if (empty($supportedCurrencies)) {
            throw new \InvalidArgumentException('At least one currency must be supported');
        }
    }

    /**
     * Vérifie si une devise est supportée par cette méthode de paiement
     */
    public function supportsCurrency(string $currencyCode): bool
    {
        return in_array($currencyCode, $this->supportedCurrencies, true);
    }

    /**
     * Vérifie si un montant est dans les limites acceptées
     */
    public function isAmountValid(float $amount): bool
    {
        return $amount >= $this->minAmount && $amount <= $this->maxAmount;
    }
}
