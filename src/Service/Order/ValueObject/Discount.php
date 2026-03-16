<?php

declare(strict_types=1);

namespace App\Service\Order\ValueObject;

/**
 * ValueObject représentant une réduction
 *
 * SOLID Principle: Single Responsibility
 * - Encapsule le type de réduction (pourcentage ou montant fixe)
 * - Calcule la réduction sur un montant donné
 */
final class Discount
{
    public function __construct(
        private readonly string $type, // 'percentage' ou 'fixed'
        private readonly float $value,
        private readonly string $reason = ''
    ) {
        if (!in_array($type, ['percentage', 'fixed'])) {
            throw new \InvalidArgumentException('Type must be percentage or fixed');
        }

        if ($type === 'percentage' && ($value < 0 || $value > 100)) {
            throw new \InvalidArgumentException('Percentage must be between 0 and 100');
        }

        if ($type === 'fixed' && $value < 0) {
            throw new \InvalidArgumentException('Fixed discount cannot be negative');
        }
    }

    public function apply(Money $amount): Money
    {
        if ($this->type === 'percentage') {
            $discount = $amount->getAmount() * ($this->value / 100);
            return new Money($amount->getAmount() - $discount, $amount->getCurrency());
        }

        // Fixed amount
        $newAmount = max(0, $amount->getAmount() - $this->value);
        return new Money($newAmount, $amount->getCurrency());
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getReason(): string
    {
        return $this->reason;
    }
}
