<?php

declare(strict_types=1);

namespace App\Service\Payment\Exception;

/**
 * Exception levée quand un moyen de paiement demandé n'existe pas
 */
class PaymentMethodNotFoundException extends PaymentException
{
    public function __construct(string $methodName, array $available)
    {
        $list = implode(', ', $available);
        parent::__construct("Moyen de paiement '{$methodName}' introuvable. Disponibles : {$list}");
    }
}
