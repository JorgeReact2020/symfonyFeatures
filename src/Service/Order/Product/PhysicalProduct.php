<?php

declare(strict_types=1);

namespace App\Service\Order\Product;

use App\Service\Order\Interface\ProductInterface;
use App\Service\Order\Interface\ShippableInterface;
use App\Service\Order\ValueObject\{Money, ProductType, Weight};

class PhysicalProduct implements ProductInterface, ShippableInterface
{
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly Money $price,
        private readonly Weight $weight
    ) {}

    public function getId(): string { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getPrice(): Money { return $this->price; }
    public function getType(): ProductType { return ProductType::PHYSICAL; }
    public function getDescription(): string { return ''; }
    public function getWeight(): Weight { return $this->weight; }
    public function getDimensions(): array { return []; }
    public function requiresSpecialHandling(): bool { return false; }
}
