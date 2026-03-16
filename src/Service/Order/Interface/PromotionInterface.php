<?php

declare(strict_types=1);

namespace App\Service\Order\Interface;

use App\Service\Order\DTO\CreateOrderDTO;
use App\Service\Order\ValueObject\Discount;

interface PromotionInterface
{
    public function getName(): string;
    
    public function isApplicable(CreateOrderDTO $order): bool;
    
    public function apply(CreateOrderDTO $order): Discount;
    
    public function getPriority(): int;
}
