<?php

declare(strict_types=1);

namespace App\Service\Payment\Exception;

/**
 * Exception levée quand un montant est invalide selon les règles métier
 * (min/max définis par le business)
 */
class InvalidAmountException extends PaymentException
{
    public static function tooLow(float $amount, float $minimum): self
    {
        return new self("Montant {$amount}€ trop bas. Minimum requis : {$minimum}€");
    }

    public static function tooHigh(float $amount, float $maximum): self
    {
        return new self("Montant {$amount}€ trop élevé. Maximum autorisé : {$maximum}€");
    }
}
