<?php

declare(strict_types=1);

namespace App\Service\Payment\FeeCalculator;

use App\Service\Payment\Interface\FeeCalculatorInterface;

/**
 * Single Responsibility Principle: Calcule UNIQUEMENT les frais PayPal
 * Dependency Inversion Principle: Implémente FeeCalculatorInterface
 *
 * Frais PayPal: 3.4% + 0.35€
 */
class PayPalFeeCalculator implements FeeCalculatorInterface
{
    private const PERCENTAGE = 3.4;
    private const FIXED_FEE = 0.35;

    public function calculate(float $amount): float
    {
        return round(($amount * self::PERCENTAGE / 100) + self::FIXED_FEE, 2);
    }

    public function getDescription(): string
    {
        return self::PERCENTAGE . '% + ' . self::FIXED_FEE . '€';
    }
}
