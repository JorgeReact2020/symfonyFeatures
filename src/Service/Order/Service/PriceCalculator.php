<?php
declare(strict_types=1);
namespace App\Service\Order\Service;
use App\Service\Order\Interface\PriceCalculatorInterface;
use App\Service\Order\DTO\CreateOrderDTO;
use App\Service\Order\ValueObject\Money;

class PriceCalculator implements PriceCalculatorInterface
{
    public function calculateProductsTotal(CreateOrderDTO $order): Money
    {
        $total = new Money(0, 'EUR');
        foreach ($order->products as $product) {
            $total = $total->add($product->getPrice());
        }
        return $total;
    }
}
