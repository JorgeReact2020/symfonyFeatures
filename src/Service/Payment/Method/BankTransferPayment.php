<?php

declare(strict_types=1);

namespace App\Service\Payment\Method;

use App\Service\Payment\DTO\PaymentDTO;
use App\Service\Payment\DTO\TransactionResult;
use App\Service\Payment\Exception\RefundNotSupportedException;
use App\Service\Payment\Interface\FeeCalculatorInterface;
use App\Service\Payment\Interface\PaymentMethodInterface;
use App\Service\Payment\ValueObject\PaymentConstraints;

/**
 * Bank Transfer Payment Method (SEPA)
 *
 * Contraintes SEPA (réelles) :
 * - Minimum : 100€ (minimum pratique pour les virements)
 * - Maximum : 50,000€ (limite anti-blanchiment sans justificatif)
 * - Devises : EUR UNIQUEMENT (SEPA = Single Euro Payments Area)
 */
class BankTransferPayment implements PaymentMethodInterface
{
    // Contraintes spécifiques aux virements SEPA
    private const MIN_AMOUNT = 100.0;
    private const MAX_AMOUNT = 50000.0;
    private const SUPPORTED_CURRENCIES = ['EUR'];  // SEPA = EUR only!

    public function __construct(private readonly FeeCalculatorInterface $feeCalculator) {}

    public function charge(PaymentDTO $payment): TransactionResult
    {
        $amount = $payment->amount->getValue();
        $fees = $this->feeCalculator->calculate($amount);
        $transactionId = "banktransfer_" . uniqid();

        return TransactionResult::success(
            transactionId: $transactionId,
            paymentMethod: $this->getName(),
            amount: $amount,
            fees: $fees,
            currency: $payment->currency->getCode(),
            customerId: $payment->customerId,
            metadata: array_merge($payment->metadata, ["processing_delay" => "2-3 days"])
        );
    }

    public function refund(string $transactionId, float $amount, string $currency): TransactionResult
    {
        throw new RefundNotSupportedException(
            "Bank transfers cannot be automatically refunded. Manual process required."
        );
    }

    public function verify(): bool { return true; }

    public function getName(): string { return "bank_transfer"; }

    public function getConstraints(): PaymentConstraints
    {
        return new PaymentConstraints(
            minAmount: self::MIN_AMOUNT,
            maxAmount: self::MAX_AMOUNT,
            supportedCurrencies: self::SUPPORTED_CURRENCIES
        );
    }

    public function getFeeCalculator(): FeeCalculatorInterface
    {
        return $this->feeCalculator;
    }
}
