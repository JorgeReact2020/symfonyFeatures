<?php

declare(strict_types=1);

namespace App\Service\Payment\FeeCalculator;

use App\Service\Payment\Interface\FeeCalculatorInterface;

/**
 * Single Responsibility Principle: Calcule UNIQUEMENT les frais virement bancaire
 * Dependency Inversion Principle: Implémente FeeCalculatorInterface
 *
 * Frais Bank Transfer: 0€ (gratuit)
 */
class BankTransferFeeCalculator implements FeeCalculatorInterface
{
    public function calculate(float $amount): float
    {
        return 0.0;
    }

    public function getDescription(): string
    {
        return 'Gratuit';
    }
}
