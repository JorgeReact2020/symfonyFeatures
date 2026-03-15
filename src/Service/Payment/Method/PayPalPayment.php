<?php

declare(strict_types=1);

namespace App\Service\Payment\Method;

use App\Service\Payment\DTO\PaymentDTO;
use App\Service\Payment\DTO\TransactionResult;
use App\Service\Payment\Interface\FeeCalculatorInterface;
use App\Service\Payment\Interface\PaymentMethodInterface;
use App\Service\Payment\ValueObject\PaymentConstraints;

/**
 * PayPal Payment Method
 *
 * Contraintes PayPal (réelles) :
 * - Minimum : 1.00€ (limite PayPal)
 * - Maximum : 10,000€ (limite par transaction standard)
 * - Devises : EUR, USD, GBP, CNY, etc.
 */
class PayPalPayment implements PaymentMethodInterface
{
    // Contraintes spécifiques à PayPal
    private const MIN_AMOUNT = 1.0;
    private const MAX_AMOUNT = 10000.0;
    private const SUPPORTED_CURRENCIES = ['EUR', 'USD', 'GBP', 'CNY'];

    public function __construct(private readonly FeeCalculatorInterface $feeCalculator) {}

    public function charge(PaymentDTO $payment): TransactionResult
    {
        $amount = $payment->amount->getValue();
        $fees = $this->feeCalculator->calculate($amount);
        $transactionId = "paypal_" . uniqid();

        return TransactionResult::success(
            transactionId: $transactionId,
            paymentMethod: $this->getName(),
            amount: $amount,
            fees: $fees,
            currency: $payment->currency->getCode(),
            customerId: $payment->customerId,
            metadata: $payment->metadata
        );
    }

    public function refund(string $transactionId, float $amount, string $currency): TransactionResult
    {
        $refundId = "paypal_refund_" . uniqid();

        return TransactionResult::success(
            transactionId: $refundId,
            paymentMethod: $this->getName(),
            amount: -$amount,
            fees: 0.0,
            currency: $currency,
            customerId: "unknown",
            metadata: ["original_transaction" => $transactionId]
        );
    }

    public function verify(): bool { return true; }

    public function getName(): string { return "paypal"; }

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
