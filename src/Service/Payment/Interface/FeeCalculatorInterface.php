<?php

declare(strict_types=1);

namespace App\Service\Payment\Interface;

/**
 * Interface FeeCalculatorInterface
 *
 * Calcule les frais de transaction pour un moyen de paiement
 *
 * SOLID Principle: Single Responsibility (SRP)
 * - Cette interface a UNE seule responsabilité: calculer les frais
 * - La logique de paiement est séparée dans PaymentMethodInterface
 */
interface FeeCalculatorInterface
{
    /**
     * Calcule les frais pour un montant donné
     *
     * @param float $amount Montant sur lequel calculer les frais
     * @return float Montant des frais
     */
    public function calculate(float $amount): float;

    /**
     * Retourne une description des frais appliqués (ex: "2.9% + 0.30€")
     *
     * @return string
     */
    public function getDescription(): string;
}
