<?php
declare(strict_types=1);
namespace App\Service\Order\Promotion;
use App\Service\Order\Interface\PromotionInterface;
use App\Service\Order\DTO\CreateOrderDTO;
use App\Service\Order\ValueObject\Discount;

class SalesPromotion implements PromotionInterface
{
    public function getName(): string { return 'Sales'; }
    public function getPriority(): int { return 1; }
    public function isApplicable(CreateOrderDTO $order): bool { return count($order->products) >= 3; }
    public function apply(CreateOrderDTO $order): Discount { return new Discount('fixed', 15.0, 'Bulk 15€'); }
}
