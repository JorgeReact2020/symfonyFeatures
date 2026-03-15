<?php

declare(strict_types=1);

namespace App\Service\Payment\FeeCalculator;

use App\Service\Payment\Interface\FeeCalculatorInterface;

/**
 * Calcule les frais Stripe
 *
 * SOLID Principle: Single Responsibility (SRP)
 * - Calcule UNIQUEMENT les frais Stripe
 *
 * SOLID Principle: Dependency Inversion (DIP)
 * - Implémente FeeCalculatorInterface
 */
class StripeFeeCalculator implements FeeCalculatorInterface
{
    private const PERCENTAGE = 2.9;
    private const FIXED_FEE = 0.30;

    public function calculate(float $amount): float
    {
        return round(($amount * self::PERCENTAGE / 100) + self::FIXED_FEE, 2);
    }

    public function getDescription(): string
    {
        return self::PERCENTAGE . '% + ' . self::FIXED_FEE . '€';
    }
}
