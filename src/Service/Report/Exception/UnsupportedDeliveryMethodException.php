<?php

declare(strict_types=1);

namespace App\Service\Report\Exception;

/**
 * Exception levée quand une méthode de livraison n'est pas supportée
 */
final class UnsupportedDeliveryMethodException extends ReportException
{
    public static function method(string $method, array $availableMethods): self
    {
        return new self(
            sprintf(
                'La méthode de livraison "%s" n\'est pas supportée. Méthodes disponibles: %s',
                $method,
                implode(', ', $availableMethods)
            )
        );
    }
}
