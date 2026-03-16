<?php
declare(strict_types=1);
namespace App\Service\Order\Promotion;
use App\Service\Order\Interface\PromotionInterface;
use App\Service\Order\DTO\CreateOrderDTO;
use App\Service\Order\ValueObject\Discount;

class LoyaltyPromotion implements PromotionInterface
{
    public function getName(): string { return 'Loyalty'; }
    public function getPriority(): int { return 5; }
    public function isApplicable(CreateOrderDTO $order): bool { return true; }
    public function apply(CreateOrderDTO $order): Discount { return new Discount('percentage', 5, 'Loyalty 5%'); }
}
