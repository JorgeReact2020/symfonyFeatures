<?php

declare(strict_types=1);

namespace App\Service\Report\Exception;

/**
 * Exception levée quand un format d'export n'est pas supporté
 */
final class UnsupportedFormatException extends ReportException
{
    public static function format(string $format, array $availableFormats): self
    {
        return new self(
            sprintf(
                'Le format "%s" n\'est pas supporté. Formats disponibles: %s',
                $format,
                implode(', ', $availableFormats)
            )
        );
    }
}