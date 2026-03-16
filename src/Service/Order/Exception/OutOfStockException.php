<?php

declare(strict_types=1);

namespace App\Service\Order\Exception;

use App\Service\Order\Interface\ProductInterface;

class OutOfStockException extends \RuntimeException
{
    public function __construct(ProductInterface $product, int $requestedQuantity, int $availableStock)
    {
        parent::__construct(sprintf(
            'Product "%s" (ID: %s) is out of stock. Requested: %d, Available: %d',
            $product->getName(),
            $product->getId(),
            $requestedQuantity,
            $availableStock
        ));
    }
}
