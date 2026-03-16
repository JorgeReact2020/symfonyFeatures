<?php

declare(strict_types=1);

namespace App\Service\Order\Interface;

use App\Service\Order\ValueObject\Weight;

/**
 * Interface pour les produits EXPÉDIABLES
 *
 * SOLID Principle: Interface Segregation (ISP)
 * - Capacité optionnelle séparée de ProductInterface
 * - Implémentée UNIQUEMENT par PhysicalProduct
 * - DigitalProduct et ServiceProduct n'ont PAS à l'implémenter
 *
 * Démonstration ISP:
 * - Si calculateShippingCost() était dans ProductInterface,
 *   DigitalProduct devrait throw exception (violation LSP)
 * - Avec ISP, on teste instanceof ShippableInterface avant d'appeler
 */
interface ShippableInterface
{
    public function getWeight(): Weight;

    public function getDimensions(): array; // ['length' => 10, 'width' => 5, 'height' => 3]

    public function requiresSpecialHandling(): bool;
}
