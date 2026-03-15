<?php

declare(strict_types=1);

namespace App\Service\Payment\ValueObject;

use InvalidArgumentException;

/**
 * ValueObject immutable pour représenter une devise (ISO 4217)
 *
 * Invariant technique : code devise valide (3 caractères alphabétiques)
 *
 * Note: La validation de la devise supportée par un payment method
 * est maintenant faite par le PaymentValidator avec les PaymentConstraints
 * spécifiques à chaque méthode (Stripe != PayPal != SEPA)
 */
final class Currency
{
    private readonly string $code;

    public function __construct(string $code)
    {
        $upperCode = strtoupper(trim($code));

        // Valider FORMAT uniquement (3 caractères alphabétiques)
        if (strlen($upperCode) !== 3 || !ctype_alpha($upperCode)) {
            throw new InvalidArgumentException(
                "Invalid currency code format '$code'. Expected 3 alphabetic characters (ISO 4217)."
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
