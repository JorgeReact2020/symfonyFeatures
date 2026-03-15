<?php

declare(strict_types=1);

namespace App\Service\Payment\Interface;

use App\Service\Payment\DTO\PaymentDTO;
use App\Service\Payment\DTO\TransactionResult;

interface PaymentMethodInterface
{
    public function charge(PaymentDTO $payment): TransactionResult;

    public function refund(string $transactionId, float $amount, string $currency): TransactionResult;

    public function verify(): bool;

    public function getName(): string;
}
