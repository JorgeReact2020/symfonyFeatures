<?php

declare(strict_types=1);

namespace App\Service\Order\Service;

use App\Service\Order\Interface\{PriceCalculatorInterface, StockManagerInterface, PromotionInterface};
use App\Service\Order\DTO\{CreateOrderDTO, OrderConfirmationDTO};
use App\Service\Order\Exception\OutOfStockException;
use App\Service\Order\ValueObject\Money;

class OrderProcessingService
{
    public function __construct(
        private readonly PriceCalculatorInterface $priceCalculator,
        private readonly StockManagerInterface $stockManager,
        private readonly iterable $promotions
    ) {}

    public function process(CreateOrderDTO $orderDTO): OrderConfirmationDTO
    {

        foreach ($orderDTO->products as $product) {
            if (!$this->stockManager->isAvailable($product, 1)) {
                throw new OutOfStockException($product, 1, 0);
            }
        }

        $total = $this->priceCalculator->calculateProductsTotal($orderDTO);
        $totalBeforeDiscount = new Money($total->getAmount(), $total->getCurrency());

        foreach ($this->promotions as $promotion) {
            if ($promotion->isApplicable($orderDTO)) {
                $discount = $promotion->apply($orderDTO);
                $total = $discount->apply($total);
            }
        }

        return new OrderConfirmationDTO(
            orderId: uniqid('ORD-'),
            customerId: $orderDTO->customerId,
            total: $total,
            subtotal: $totalBeforeDiscount,
            estimatedDelivery: new \DateTimeImmutable('+3 days'),
            trackingNumber: uniqid('TRACK-'),
            status: 'confirmed'
        );
    }
}
