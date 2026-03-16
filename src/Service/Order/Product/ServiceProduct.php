<?php

declare(strict_types=1);

namespace App\Service\Order\Product;

use App\Service\Order\Interface\{ProductInterface, ActivableInterface};
use App\Service\Order\ValueObject\{Money, ProductType};

class ServiceProduct implements ProductInterface, ActivableInterface
{
    private array $activations = [];

    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly Money $price
    ) {}

    public function getId(): string { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getPrice(): Money { return $this->price; }
    public function getType(): ProductType { return ProductType::SERVICE; }
    public function getDescription(): string { return ''; }
    public function activate(string $customerId): void { $this->activations[$customerId] = ['active' => true, 'date' => new \DateTimeImmutable()]; }
    public function deactivate(string $customerId): void { if(isset($this->activations[$customerId])) $this->activations[$customerId]['active'] = false; }
    public function isActive(string $customerId): bool { return $this->activations[$customerId]['active'] ?? false; }
    public function getActivationDate(string $customerId): ?\DateTimeImmutable { return $this->activations[$customerId]['date'] ?? null; }
    public function getSubscriptionDuration(): ?int { return null; }
}
