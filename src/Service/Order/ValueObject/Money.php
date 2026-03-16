<?php

declare(strict_types=1);

namespace App\Service\Order\ValueObject;

/**
 * ValueObject représentant un montant monétaire
 *
 * SOLID Principle: Single Responsibility
 * - Encapsule montant + devise
 * - Empêche les erreurs de type (mélanger EUR et USD)
 * - Immutable (pas de setter)
 */
final class Money
{
    public function __construct(
        private readonly float $amount,
        private readonly string $currency = 'EUR'
    ) {
        if ($amount < 0) {
            throw new \InvalidArgumentException('Amount cannot be negative');
        }
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function add(Money $other): self
    {
        if ($this->currency !== $other->currency) {
            throw new \InvalidArgumentException('Cannot add different currencies');
        }

        return new self($this->amount + $other->amount, $this->currency);
    }

    public function subtract(Money $other): self
    {
        if ($this->currency !== $other->currency) {
            throw new \InvalidArgumentException('Cannot subtract different currencies');
        }

        return new self($this->amount - $other->amount, $this->currency);
    }

    public function multiply(float $multiplier): self
    {
        return new self($this->amount * $multiplier, $this->currency);
    }

    public function format(): string
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }
}
