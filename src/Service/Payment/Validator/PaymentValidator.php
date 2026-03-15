<?php

declare(strict_types=1);

namespace App\Service\Payment\Validator;

use App\Service\Payment\DTO\PaymentDTO;
use App\Service\Payment\Exception\InvalidAmountException;
use App\Service\Payment\Exception\SuspendedCustomerException;
use App\Service\Payment\Exception\UnsupportedCurrencyException;
use App\Service\Payment\Interface\PaymentValidatorInterface;
use App\Service\Payment\ValueObject\PaymentConstraints;

/**
 * Single Responsibility Principle: Valide UNIQUEMENT les règles métier des paiements
 *
 * Validation des règles métier avec contraintes spécifiques à la méthode :
 * - Montant minimum/maximum (spécifiques à chaque payment method)
 * - Devise supportée (varie selon le provider: Stripe = 135+, SEPA = EUR uniquement)
 * - Statut client actif
 *
 * Note: Les invariants techniques (montant > 0, format devise) sont
 * déjà garantis par les ValueObjects dans PaymentDTO
 *
 * Open/Closed Principle:
 * - Chaque PaymentMethod définit SES contraintes (pas de modification du validator)
 * - Extensible sans modification (nouveaux methods = nouvelles constraints)
 *
 * Dependency Inversion Principle:
 * - Dépend des abstractions (PaymentConstraints) pas des implémentations
 */
class PaymentValidator implements PaymentValidatorInterface
{
    // Simuler une liste de clients suspendus (en prod: BDD)
    private const SUSPENDED_CUSTOMERS = ["customer_suspended", "customer_fraud"];

    /**
     * Valide un paiement avec les contraintes spécifiques de la méthode choisie
     *
     * @param PaymentDTO $payment Le paiement à valider
     * @param PaymentConstraints $constraints Contraintes du payment method (min/max, devises)
     */
    public function validate(PaymentDTO $payment, PaymentConstraints $constraints): void
    {
        $amount = $payment->amount->getValue();
        $currency = $payment->currency->getCode();

        // Valider montant minimum (spécifique au payment method)
        if ($amount < $constraints->minAmount) {
            throw InvalidAmountException::tooLow($amount, $constraints->minAmount);
        }

        // Valider montant maximum (spécifique au payment method)
        if ($amount > $constraints->maxAmount) {
            throw InvalidAmountException::tooHigh($amount, $constraints->maxAmount);
        }

        // Valider devise supportée par le payment method
        if (!$constraints->supportsCurrency($currency)) {
            throw UnsupportedCurrencyException::withSupportedList(
                $payment->currency,
                $payment->methodName,
                $constraints->supportedCurrencies
            );
        }

        // Vérifier statut du client
        if (in_array($payment->customerId, self::SUSPENDED_CUSTOMERS, true)) {
            throw new SuspendedCustomerException($payment->customerId);
        }
    }
}
