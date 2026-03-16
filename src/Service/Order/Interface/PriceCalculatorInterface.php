<?php

declare(strict_types=1);

namespace App\Service\Order\Interface;

use App\Service\Order\DTO\CreateOrderDTO;
use App\Service\Order\ValueObject\Money;

interface PriceCalculatorInterface
{
    public function calculateProductsTotal(CreateOrderDTO $order): Money;
}
