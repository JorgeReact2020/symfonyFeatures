<?php

declare(strict_types=1);

namespace App\Service\Order\DTO;

use App\Service\Order\Interface\ProductInterface;

final class CreateOrderDTO
{
    /**
     * @param array<ProductInterface> $products
     */
    public function __construct(
        public readonly string $customerId,
        public readonly array $products,
        public readonly ?string $promoCode = null,
        public readonly ?string $shippingMethod = null
    ) {
    }
}
