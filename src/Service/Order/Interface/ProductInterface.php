<?php

declare(strict_types=1);

namespace App\Service\Order\Interface;

use App\Service\Order\ValueObject\Money;
use App\Service\Order\ValueObject\ProductType;

/**
 * Interface de base pour TOUS les produits
 *
 * SOLID Principle: Interface Segregation (ISP)
 * - Interface minimale commune à TOUS les produits
 * - Les capacités spécifiques sont dans des interfaces séparées
 * - Un produit numérique n'est PAS forcé d'implémenter calculateShippingCost()
 */
interface ProductInterface
{
    public function getId(): string;

    public function getName(): string;

    public function getPrice(): Money;

    public function getType(): ProductType;

    public function getDescription(): string;
}
