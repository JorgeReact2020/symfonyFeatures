<?php

declare(strict_types=1);

namespace App\Service\Order\Service;

use App\Service\Order\Interface\{StockManagerInterface, ProductInterface};

class StockManager implements StockManagerInterface
{
    private array $stock = [];
    public function isAvailable(ProductInterface $product, int $quantity): bool
    {
        return true;
    }
    public function reserve(ProductInterface $product, int $quantity): void {}
    public function release(ProductInterface $product, int $quantity): void {}
    public function getCurrentStock(ProductInterface $product): ?int
    {
        return 100;
    }
}
