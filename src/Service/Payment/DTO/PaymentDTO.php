<?php

declare(strict_types=1);

namespace App\Service\Payment\DTO;

use App\Service\Payment\ValueObject\Amount;
use App\Service\Payment\ValueObject\Currency;

/**
 * Data Transfer Object immutable pour représenter un paiement
 *
 * Garanties via ValueObjects:
 * - Amount garantit montant > 0
 * - Currency garantit devise valide
 *
 * Validation métier (min/max, statut client) dans PaymentValidator
 */
final readonly class PaymentDTO
{
    public function __construct(
        public Amount $amount,
        public Currency $currency,
        public string $customerId,
        public string $methodName,
        public array $metadata = []
    ) {}

    /**
     * Factory methods pour créer facilement des paiements
     */
    public static function create(float $amount, string $currency, string $customerId, string $methodName, array $metadata = []): self
    {
        return new self(
            new Amount($amount),
            new Currency($currency),
            $customerId,
            $methodName,
            $metadata
        );
    }
}
