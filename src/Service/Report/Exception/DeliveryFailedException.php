<?php

declare(strict_types=1);

namespace App\Service\Report\Exception;

/**
 * Exception levée quand la livraison échoue
 */
final class DeliveryFailedException extends ReportException
{
    public static function forMethod(string $method, string $reason): self
    {
        return new self(
            sprintf(
                'La livraison via "%s" a échoué: %s',
                $method,
                $reason
            )
        );
    }
}