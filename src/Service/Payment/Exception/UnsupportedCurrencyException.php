<?php

declare(strict_types=1);

namespace App\Service\Payment\Exception;

use App\Service\Payment\ValueObject\Currency;

/**
 * Exception levée quand une devise n'est pas supportée par une méthode de paiement
 *
 * Exemple : Essayer de payer en CNY avec BankTransfer (qui supporte seulement EUR)
 */
final class UnsupportedCurrencyException extends PaymentException
{
    /**
     * Crée une exception pour une devise non supportée par une méthode spécifique
     *
     * @param Currency $currency La devise rejetée
     * @param string $methodName Le nom de la méthode de paiement (ex: "stripe", "bank_transfer")
     */
    public static function forMethod(Currency $currency, string $methodName): self
    {
        return new self(
            sprintf(
                'Currency %s is not supported by payment method "%s"',
                $currency->getCode(),
                $methodName
            )
        );
    }

    /**
     * Crée une exception avec la liste des devises supportées
     *
     * @param Currency $currency La devise rejetée
     * @param string $methodName Le nom de la méthode
     * @param array<string> $supportedCurrencies Liste des devises acceptées
     */
    public static function withSupportedList(
        Currency $currency,
        string $methodName,
        array $supportedCurrencies
    ): self {
        return new self(
            sprintf(
                'Currency %s is not supported by payment method "%s". Supported currencies: %s',
                $currency->getCode(),
                $methodName,
                implode(', ', $supportedCurrencies)
            )
        );
    }
}
