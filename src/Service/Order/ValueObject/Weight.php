<?php

declare(strict_types=1);

namespace App\Service\Order\ValueObject;

/**
 * ValueObject représentant un poids
 *
 * SOLID Principle: Single Responsibility
 * - Encapsule valeur + unité
 * - Empêche les erreurs (mélanger kg et g)
 */
final class Weight
{
    public function __construct(
        private readonly float $value,
        private readonly string $unit = 'kg'
    ) {
        if ($value < 0) {
            throw new \InvalidArgumentException('Weight cannot be negative');
        }

        if (!in_array($unit, ['kg', 'g', 'lb'])) {
            throw new \InvalidArgumentException('Invalid weight unit');
        }
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }

    public function toKilograms(): float
    {
        return match($this->unit) {
            'kg' => $this->value,
            'g' => $this->value / 1000,
            'lb' => $this->value * 0.453592,
        };
    }
}
