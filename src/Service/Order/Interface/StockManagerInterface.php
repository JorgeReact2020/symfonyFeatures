<?php

declare(strict_types=1);

namespace App\Service\Order\Interface;

interface StockManagerInterface
{
    public function isAvailable(ProductInterface $product, int $quantity): bool;
    
    public function reserve(ProductInterface $product, int $quantity): void;
    
    public function release(ProductInterface $product, int $quantity): void;
    
    public function getCurrentStock(ProductInterface $product): ?int;
}
