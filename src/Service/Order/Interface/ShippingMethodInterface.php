<?php

declare(strict_types=1);

namespace App\Service\Order\Interface;

use App\Service\Order\DTO\CreateOrderDTO;
use App\Service\Order\ValueObject\Money;

interface ShippingMethodInterface
{
    public function getName(): string;
    
    public function calculateCost(CreateOrderDTO $order): Money;
    
    public function getEstimatedDeliveryDays(): int;
    
    public function isAvailableFor(CreateOrderDTO $order): bool;
}
