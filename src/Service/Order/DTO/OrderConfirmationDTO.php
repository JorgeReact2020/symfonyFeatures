<?php

declare(strict_types=1);

namespace App\Service\Order\DTO;

use App\Service\Order\ValueObject\Money;

final class OrderConfirmationDTO
{
    public function __construct(
        public readonly string $orderId,
        public readonly string $customerId,
        public readonly Money $total,
        public readonly ?Money $subtotal,
        public readonly ?\DateTimeImmutable $estimatedDelivery = null,
        public readonly ?string $trackingNumber = null,
        public readonly string $status = 'confirmed'
    ) {
    }
}
