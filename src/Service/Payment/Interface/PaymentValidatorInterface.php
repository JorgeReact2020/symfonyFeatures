<?php

declare(strict_types=1);

namespace App\Service\Payment\Interface;

use App\Service\Payment\DTO\PaymentDTO;

/**
 * Single Responsibility Principle: Ne fait QUE valider les règles métier
 * Open/Closed Principle: Peut être étendu avec différentes stratégies (B2C, B2B, VIP)
 */
interface PaymentValidatorInterface
{
    /**
     * Valide qu'un paiement respecte les règles métier
     *
     * @throws InvalidPaymentException si le paiement est invalide
     */
    public function validate(PaymentDTO $payment): void;
}
