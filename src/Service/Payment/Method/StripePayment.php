<?php

declare(strict_types=1);

namespace App\Service\Payment\Method;

use App\Service\Payment\DTO\PaymentDTO;
use App\Service\Payment\DTO\TransactionResult;
use App\Service\Payment\Interface\FeeCalculatorInterface;
use App\Service\Payment\Interface\PaymentMethodInterface;

class StripePayment implements PaymentMethodInterface
{
    public function __construct(private readonly FeeCalculatorInterface $feeCalculator) {}

    public function charge(PaymentDTO $payment): TransactionResult
    {
        $amount = $payment->amount->getValue();
        $fees = $this->feeCalculator->calculate($amount);
        $transactionId = "stripe_" . uniqid();

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
        $refundId = "stripe_refund_" . uniqid();

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

    public function getName(): string { return "stripe"; }
}
