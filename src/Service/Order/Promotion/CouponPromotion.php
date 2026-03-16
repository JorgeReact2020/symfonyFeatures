<?php

declare(strict_types=1);

namespace App\Service\Order\Promotion;

use App\Service\Order\Interface\PromotionInterface;
use App\Service\Order\DTO\CreateOrderDTO;
use App\Service\Order\ValueObject\Discount;

class CouponPromotion implements PromotionInterface
{
    private const CODES = ['WELCOME10' => 10];
    public function getName(): string
    {
        return 'Coupon';
    }
    public function getPriority(): int
    {
        return 10;
    }
    public function isApplicable(CreateOrderDTO $order): bool
    {
        return $order->promoCode && isset(self::CODES[$order->promoCode]);
    }
    public function apply(CreateOrderDTO $order): Discount
    {
        return new Discount('percentage', self::CODES[$order->promoCode], 'Coupon');
    }
}
