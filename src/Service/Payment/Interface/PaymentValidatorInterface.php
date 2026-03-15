<?php

declare(strict_types=1);

namespace App\Service\Payment\Interface;

use App\Service\Payment\DTO\PaymentDTO;
use App\Service\Payment\ValueObject\PaymentConstraints;

/**
 * Single Responsibility Principle: Ne fait QUE valider les règles métier
 * Open/Closed Principle: Peut être étendu avec différentes stratégies (B2C, B2B, VIP)
 */
interface PaymentValidatorInterface
{
    /**
     * Valide qu'un paiement respecte les règles métier avec les contraintes spécifiques
     * de la méthode de paiement choisie
     *
     * @param PaymentDTO $payment Le paiement à valider
     * @param PaymentConstraints $constraints Les contraintes de la méthode (min/max, devises)
     * @throws InvalidPaymentException si le paiement est invalide
     */
    public function validate(PaymentDTO $payment, PaymentConstraints $constraints): void;
}
