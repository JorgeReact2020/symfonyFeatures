<?php

declare(strict_types=1);

namespace App\Service\Payment\ValueObject;

use InvalidArgumentException;

/**
 * ValueObject immutable pour garantir qu'un montant est TOUJOURS positif
 *
 * Invariant technique : montant > 0
 * (Les limites min/max métier sont dans PaymentValidator)
 */
final readonly class Amount
{
    public function __construct(private float $value)
    {
        if ($value <= 0) {
            throw new InvalidArgumentException("Le montant doit être strictement positif");
        }
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function format(): string
    {
        return number_format($this->value, 2, ',', ' ') . '€';
    }
}
