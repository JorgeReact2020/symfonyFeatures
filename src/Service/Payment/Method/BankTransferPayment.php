<?php

declare(strict_types=1);

namespace App\Service\Payment\Method;

use App\Service\Payment\DTO\PaymentDTO;
use App\Service\Payment\DTO\TransactionResult;
use App\Service\Payment\Exception\RefundNotSupportedException;
use App\Service\Payment\Interface\FeeCalculatorInterface;
use App\Service\Payment\Interface\PaymentMethodInterface;

class BankTransferPayment implements PaymentMethodInterface
{
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
}
