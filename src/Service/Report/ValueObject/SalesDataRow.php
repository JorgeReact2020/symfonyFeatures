<?php

declare(strict_types=1);

namespace App\Service\Report\ValueObject;

/**
 * ValueObject représentant une ligne de données de vente
 *
 * ValueObject car:
 * - Possède des INVARIANTS métier (quantity > 0, total = qty × price)
 * - IMMUTABLE
 * - Représente un concept métier avec règles
 */
final class SalesDataRow
{
    private \DateTimeImmutable $date;
    private string $productName;
    private int $quantity;
    private float $unitPrice;
    private float $total;

    /**
     * @throws \InvalidArgumentException
     */
    public function __construct(
        \DateTimeInterface $date,
        string $productName,
        int $quantity,
        float $unitPrice
    ) {
        $this->date = \DateTimeImmutable::createFromInterface($date);
        $this->productName = trim($productName);
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;
        $this->total = $quantity * $unitPrice;

        $this->validate();
    }

    private function validate(): void
    {
        // Règle métier 1: nom de produit obligatoire
        if (empty($this->productName)) {
            throw new \InvalidArgumentException('Le nom du produit ne peut pas être vide');
        }

        // Règle métier 2: quantité positive
        if ($this->quantity <= 0) {
            throw new \InvalidArgumentException(
                "La quantité doit être positive (actuellement: {$this->quantity})"
            );
        }

        // Règle métier 3: prix non négatif
        if ($this->unitPrice < 0) {
            throw new \InvalidArgumentException(
                "Le prix unitaire ne peut pas être négatif (actuellement: {$this->unitPrice})"
            );
        }
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * Pour l'export (conversion en array)
     */
    public function toArray(): array
    {
        return [
            'date' => $this->date->format('Y-m-d'),
            'product' => $this->productName,
            'quantity' => $this->quantity,
            'unitPrice' => $this->unitPrice,
            'total' => $this->total,
        ];
    }
}