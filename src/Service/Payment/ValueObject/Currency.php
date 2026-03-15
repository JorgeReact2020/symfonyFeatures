<?php

declare(strict_types=1);

namespace App\Service\Payment\ValueObject;

use InvalidArgumentException;

/**
 * ValueObject immutable pour garantir qu'une devise est TOUJOURS valide
 *
 * Invariant technique : devise existe dans la liste supportée
 */
final class Currency
{
    private const SUPPORTED = ['EUR', 'USD', 'GBP'];

    private readonly string $code;

    public function __construct(string $code)
    {
        $upperCode = strtoupper($code);

        if (!in_array($upperCode, self::SUPPORTED, true)) {
            throw new InvalidArgumentException(
                "Devise '$code' non supportée. Devises acceptées : " . implode(', ', self::SUPPORTED)
            );
        }

        // Normaliser en majuscules
        $this->code = $upperCode;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getSymbol(): string
    {
        return match ($this->code) {
            'EUR' => '€',
            'USD' => '$',
            'GBP' => '£',
            default => $this->code,
        };
    }
}
