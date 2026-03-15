<?php

declare(strict_types=1);

namespace App\Service\Payment\DTO;

use DateTimeImmutable;

/**
 * Résultat d'une transaction (charge ou remboursement)
 *
 * Immutable pour garantir l'intégrité des données de transaction
 */
final readonly class TransactionResult
{
    public function __construct(
        public string $transactionId,
        public string $paymentMethod,
        public float $amount,
        public float $fees,
        public string $currency,
        public string $customerId,
        public bool $success,
        public ?string $errorMessage = null,
        public ?DateTimeImmutable $processedAt = null,
        public array $metadata = []
    ) {}

    public static function success(
        string $transactionId,
        string $paymentMethod,
        float $amount,
        float $fees,
        string $currency,
        string $customerId,
        array $metadata = []
    ): self {
        return new self(
            transactionId: $transactionId,
            paymentMethod: $paymentMethod,
            amount: $amount,
            fees: $fees,
            currency: $currency,
            customerId: $customerId,
            success: true,
            errorMessage: null,
            processedAt: new DateTimeImmutable(),
            metadata: $metadata
        );
    }

    public static function failure(
        string $paymentMethod,
        float $amount,
        string $currency,
        string $customerId,
        string $errorMessage,
        array $metadata = []
    ): self {
        return new self(
            transactionId: '',
            paymentMethod: $paymentMethod,
            amount: $amount,
            fees: 0.0,
            currency: $currency,
            customerId: $customerId,
            success: false,
            errorMessage: $errorMessage,
            processedAt: new DateTimeImmutable(),
            metadata: $metadata
        );
    }

    public function getTotalAmount(): float
    {
        return $this->amount + $this->fees;
    }
}
