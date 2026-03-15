<?php

declare(strict_types=1);

namespace App\Service\Payment\Validator;

use App\Service\Payment\DTO\PaymentDTO;
use App\Service\Payment\Exception\InvalidAmountException;
use App\Service\Payment\Exception\SuspendedCustomerException;
use App\Service\Payment\Interface\PaymentValidatorInterface;

/**
 * Single Responsibility Principle: Valide UNIQUEMENT les règles métier des paiements
 *
 * Validation des règles métier (peuvent varier selon le contexte) :
 * - Montant minimum: 5€
 * - Montant maximum: 5000€
 * - Statut client actif
 *
 * Note: Les invariants techniques (montant > 0, devise valide) sont
 * déjà garantis par les ValueObjects dans PaymentDTO
 *
 * Open/Closed Principle: Extensible via héritage (B2CPaymentValidator, B2BPaymentValidator)
 */
class PaymentValidator implements PaymentValidatorInterface
{
    private const MIN_AMOUNT = 5.0;
    private const MAX_AMOUNT = 5000.0;

    // Simuler une liste de clients suspendus (en prod: BDD)
    private const SUSPENDED_CUSTOMERS = ["customer_suspended", "customer_fraud"];

    public function validate(PaymentDTO $payment): void
    {
        $amount = $payment->amount->getValue();

        // Valider montant minimum
        if ($amount < self::MIN_AMOUNT) {
            throw InvalidAmountException::tooLow($amount, self::MIN_AMOUNT);
        }

        // Valider montant maximum
        if ($amount > self::MAX_AMOUNT) {
            throw InvalidAmountException::tooHigh($amount, self::MAX_AMOUNT);
        }

        // Vérifier statut du client
        if (in_array($payment->customerId, self::SUSPENDED_CUSTOMERS, true)) {
            throw new SuspendedCustomerException($payment->customerId);
        }
    }
}
