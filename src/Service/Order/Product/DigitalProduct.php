<?php

declare(strict_types=1);

namespace App\Service\Order\Product;

use App\Service\Order\Interface\{ProductInterface, DownloadableInterface};
use App\Service\Order\ValueObject\{Money, ProductType};

class DigitalProduct implements ProductInterface, DownloadableInterface
{
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly Money $price,
        private readonly string $downloadUrl
    ) {}

    public function getId(): string { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getPrice(): Money { return $this->price; }
    public function getType(): ProductType { return ProductType::DIGITAL; }
    public function getDescription(): string { return ''; }
    public function getDownloadUrl(): string { return $this->downloadUrl; }
    public function getFileSize(): int { return 0; }
    public function getFileFormat(): string { return 'pdf'; }
    public function getDownloadLimit(): ?int { return null; }
}
